<?php
require_once dirname(__FILE__) . '/../bootstrap.php';
use API\Middleware\TokenOverBasicAuth;
use API\Exception;
use API\Exception\ValidationException;
// General API group
set_exception_handler("e");
function e($e){
    echo "<h1>".$e->getMessage()."</h1>";
}
$authenticateForRole = function ( $role = 'member' ) {
    return function () use ( $role ) {
        $user = User::fetchFromDatabaseSomehow();
        if ( $user->belongsToRole($role) === false ) {
            $app = \Slim\Slim::getInstance();
            $app->flash('error', 'Login required');
            $app->redirect('/login');
        }
    };
};

#http://localhost/modelo/public/
$app->get('/', function () use ($app, $log) {
    echo "<h1>header</h1>";
    echo "<p>Esta e a raiz /////// </p>";
    echo "<h1>footer</h1>";
});


$app->group('/api',function () use ($app, $log) {

        $app->get('/', function () use ($app, $log) {
            $app->render('header.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
            $app->render('home.php',array('aaa' => 'aaaaaaaaaaaaaaa bbbbbbbbbb'),200);
            $app->render('footer.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
        });

  
        // Group for API Version 1
        #$app->group('/v1', $authenticateForRole('admin') , function () use ($app, $log) {
        $app->group('/v1', function () use ($app, $log) {
                // Get contacts
                $app->get('/contacts', function () use ($app, $log) {
                        #echo "oiiiiiiiiii";
                        $contacts = array();
                        $filters = array();
                        $total = 0;
                        $pages = 1;
                        // Default resultset
                        $results = \ORM::for_table('contacts');
                        // Get and sanitize filters from the URL

                        #echo "<pre>";
                        #print_r($app->request->get());die;
                        if ($rawfilters = $app->request->get()) {

                           /* 
                            $sort = preg_replace_callback('/^(\w+) (ASC|DESC)$/i',function($m){
                                return $m[2]=='DESC'?'-'.$m[1]:'+'.$m[1];
                            },$rawfilters['jtSorting']);
                            $limit = (int) $rawfilters['jtStartIndex'] + 10; // jtable
                            $offset = (int) $rawfilters['jtPageSize']; // jtable
                            */
                            #echo "<pre>";
                            #print_r($rawfilters);

                            unset(
                                $rawfilters['order'], // este aqui e por causa do bootstraptable
                                $rawfilters['limit'], // jtable
                                $rawfilters['offset'], // jtable


                                $rawfilters['jtStartIndex'],
                                $rawfilters['jtPageSize'],
                                $rawfilters['jtSorting'],

                                $rawfilters['sort'],
                                $rawfilters['fields'],
                                $rawfilters['page'],
                                $rawfilters['per_page']
                            );
                            foreach ($rawfilters as $key => $value) {
                                $filters[$key] = filter_var($value,FILTER_SANITIZE_STRING);
                            }
                        }
                        // Add filters to the query
                        if (!empty($filters)) {
                            foreach ($filters as $key => $value) {
                                if ('q' == $key) {
                                    $results->where_raw('(`firstname` LIKE ? OR `email` LIKE ?)',
                                        array('%'.$value.'%', '%'.$value.'%')
                                    );
                                } else {
                                    $results->where($key, $value);
                                }
                            }
                        }
                        // Get and sanitize field list from the URL
                        if ($fields = $app->request->get('fields')) {
                            $fields = explode(',', $fields);
                            $fields = array_map(
                                function ($field) {
                                    $field = filter_var($field,FILTER_SANITIZE_STRING);
                                    return trim($field);
                                },
                                $fields
                            );
                        }
                        // Add field list to the query
                        if (is_array($fields) && !empty($fields)) {
                            $results->select_many($fields);
                        }
                        // Manage sort options
                        // sort=firstname => ORDER BY firstname ASC
                        // sort=-firstname => ORDER BY firstname DESC
                        // sort=-firstname,email =>
                        // ORDER BY firstname DESC, email ASC
                        if ($sort = $app->request->get('sort')) {
                            
                            $sort = explode(',', $sort);
                            $sort = array_map(
                                function ($s) {
                                    $s = filter_var($s, FILTER_SANITIZE_STRING);
                                    return trim($s);
                                },
                                $sort
                            );
                            #echo "<pre>";
                            #print_r($sort);

                            foreach ($sort as $expr) {
                                #$order = substr($expr, 1, strlen($expr)-1);
                                if (substr($expr, 0, 1)=='-') {
                                    #echo $expr;    
                                    $results->order_by_desc(substr($expr, 1));
                                    #$results->order_by_desc(substr($expr, 1, strlen($expr)-1));
                                } else {
                                    $results->order_by_asc($expr);
                                }
                            }
                        }
                        // Manage pagination
                        // aqui usando limit e offset direto

                        $limit = filter_var($app->request->get('limit'),FILTER_SANITIZE_NUMBER_INT) ?: "25";
                        $offset = filter_var($app->request->get('offset'),FILTER_SANITIZE_NUMBER_INT) ?: "5";

                          

                        $results->limit($limit)->offset($offset);

/*


                        $page = filter_var($app->request->get('page'),FILTER_SANITIZE_NUMBER_INT);
                        if (!empty($page)) {
                            $perPage = filter_var($app->request->get('per_page'),FILTER_SANITIZE_NUMBER_INT);
                            if (empty($perPage)) {
                                $perPage = 10;
                            }
                            // Total after filters and before pagination limit
                            $total = $results->count();
                            // Compute the pagination Link header
                            $pages = ceil($total / $perPage);
                            // Base for all links
                            $linkBaseURL = $app->request->getUrl() . $app->request->getRootUri() . $app->request->getResourceUri();
                            // Init empty vars
                            $queryString = array();
                            $links = array();
                            $next =  '';
                            $last = '';
                            $prev =  '';
                            $first = '';
                            // Adding fields
                            if (!empty($fields)) {
                                $queryString[] = 'fields='
                                    . join(
                                        ',',
                                        array_map(
                                            function ($f) {
                                                return urlencode($f);
                                            },
                                            $fields
                                        )
                                    );
                            }
                            // Adding filters
                            if (!empty($filters)) {
                                $queryString[] = http_build_query($filters);
                            }
                            // Adding sort options
                            if (!empty($sort)) {
                                $queryString[] = 'sort='
                                    . join(
                                        ',',
                                        array_map(
                                            function ($s) {
                                                return urlencode($s);
                                            },
                                            $sort
                                        )
                                    );
                            }
                            // Next and Last link
                            if ($page < $pages) {
                                $next = $linkBaseURL . '?' . join(
                                    '&',
                                    array_merge(
                                        $queryString,
                                        array(
                                            'page=' . (string) ($page + 1),
                                            'per_page=' . $perPage
                                        )
                                    )
                                );
                                $last = $linkBaseURL . '?' . join(
                                    '&',
                                    array_merge(
                                        $queryString,
                                        array(
                                            'page=' . (string) $pages,
                                            'per_page=' . $perPage
                                        )
                                    )
                                );
                                $links[] = sprintf('<%s>; rel="next"', $next);
                                $links[] = sprintf('<%s>; rel="last"', $last);
                            }
                            // Previous and First link
                            if ($page > 1 && $page <= $pages) {
                                $prev = $linkBaseURL . '?' . join(
                                    '&',
                                    array_merge(
                                        $queryString,
                                        array(
                                            'page=' . (string) ($page - 1),
                                            'per_page=' . $perPage
                                        )
                                    )
                                );
                                $first = $linkBaseURL . '?' . join(
                                    '&',
                                    array_merge(
                                        $queryString,
                                        array(
                                            'page=1', 'per_page=' . $perPage
                                        )
                                    )
                                );
                                $links[] = sprintf('<%s>; rel="prev"', $prev);
                                $links[] = sprintf('<%s>; rel="first"', $first);
                            }
                            // Set header if required
                            if (!empty($links)) {
                                $app->response->headers->set('Link',join(',', $links));
                            }
                            $results->limit($perPage)->offset($page * $perPage - $perPage);
                        }
*/

                        $contacts = $results->find_array();
                        //======================== adding notes =========================
/*
                        foreach($contacts as $k=>$v){
                            foreach($v as $key=>$val){
                                if($key == 'id'){
                                    $id = $val;
                                    $notes = \ORM::for_table('notes')->where('contact_id', $id)->order_by_desc('id')->find_array();
                                    if (!empty($notes)) {
                                        $contacts[$k]['notes'] = $notes;
                                    }
                                    
                                }
                                
                            }
                        }
*/
                        //======================== adding notes =========================

                        if (empty($total)) {
                            $total = count($contacts);
                        }
                        $app->response->headers->set('X-Total-Count', $total);
                        #$app->response->headers->remove('X-Powered-By');    
                        #$app->response->headers->remove('Server');    

                        #echo $twig->render('contacts.html');
                        #echo "<pre>";
                        echo json_encode($contacts, JSON_PRETTY_PRINT);
                        #$response = $app->response();
                        #$response['Content-Type'] = 'application/json';
                        ##$response->body(json_encode($contacts));
                        #exit();
                        #$app->view()->setData(array('contacts' => $contacts));
                        #$app->render('contacts.html',array('data' => json_encode($contacts, JSON_PRETTY_PRINT)));

                    }
                );
                // Get contact with ID
                $app->get('/contacts/:id', function ($id) use ($app, $log) {
                        $id = filter_var( filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
                        if (false === $id) {
                            throw new ValidationException("Invalid contact ID");
                        }
                        $contact = \ORM::for_table('contacts')->find_one($id);
                        $list_notes = array();    
                        if ($contact) {
                            $output = $contact->as_array();
                            if ($app->request->get('embed') === 'notes') {
                                $notes = \ORM::for_table('notes')
                                    ->where('contact_id', $id)
                                    ->order_by_desc('id')
                                    ->find_array();
                                if (!empty($notes)) {
                                    $list_notes = $notes;
                                    $output['notes'] = $notes;
                                }
                            }
                            echo json_encode($output, JSON_PRETTY_PRINT);
                            return;
                        }
                        $app->notFound();
                    }
                )->conditions(array('id' => '[0-9]{0,4}'));
                // Adds new contact
                $app->post('/contacts',function () use ($app, $log) {
                        #echo $app->request()->getBody();
                        #$body = json_decode($app->request()->getBody());
                        #print_r($body);
                        parse_str($app->request()->getBody(), $body);
                        #echo '<hr>',$body;
                        #die;
                        # cuidado aqui sempre tem q ver como esta vindo
                        #$body = $app->request()->getBody();

                        if(empty($body)){
                            throw new Exception("Sem dados de post");
                        }
                        $errors = $app->validateContact($body);
                        #echo "<pre>";
                        #print_r($errors);

                        if (empty($errors)) {
                            $contact = \ORM::for_table('contacts')->create();
                            if (isset($body['notes'])) {
                                $notes = $body['notes'];
                                unset($body['notes']);
                            }
                            $contact->set($body);
                            if (true === $contact->save()) {
                                // Insert notes
                                if (!empty($notes)) {
                                    $contactNotes = array();
                                    foreach ($notes as $item) {
                                        $item['contact_id'] = $contact->id;
                                        $note = \ORM::for_table('notes')->create();
                                        $note->set($item);
                                        if (true === $note->save()) {
                                            $contactNotes[] = $note->as_array();
                                        }
                                    }
                                }
                                $output = $contact->as_array();
                                if (!empty($contactNotes)) {
                                    $output['notes'] = $contactNotes;
                                }
                                    
                                $jt = array();
                                $jt['Result'] = "OK";
                                $jt['Record'] = (array) $output;
                                echo json_encode($jt);

                                #echo json_encode(array('Result' => "OK"));
                                die(); // tenho q por essa resposta pro jquery

                                echo json_encode($output, JSON_PRETTY_PRINT);
                            } else {
                                throw new Exception("Unable to save contact");
                            }
                        } else {
                            #echo json_encode($errors, JSON_PRETTY_PRINT);
                            throw new ValidationException("POST - Invalid data",0,$errors);
                        }
                    }
                );
                // Update contact with ID
                $app->map('/contacts/:id', function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->find_one($id);
                        if ($contact) {
                            #$body = json_decode($app->request()->getBody(),true);
                            parse_str($app->request()->getBody(), $body);
                            $errors = $app->validateContact($body, 'update');
                            if (empty($errors)) {
                                if (isset($body['notes'])) {
                                    $notes = $body['notes'];
                                    unset($body['notes']);
                                }
                                $contact->set($body);
                                if (true === $contact->save()) {
                                    // Process notes
                                    if (!empty($notes)) {
                                        $contactNotes = array();
                                        foreach ($notes as $item) {
                                            $item['contact_id'] = $contact->id;
                                            if (empty($item['id'])) {
                                                // New note
                                                $note = \ORM::for_table('notes')->create();
                                            } else {
                                                // Existing note
                                                $note = \ORM::for_table('notes')->find_one($item['id']);
                                            }
                                            if ($note) {
                                                $note->set($item);
                                                if (true === $note->save()) {
                                                    $contactNotes[] = $note->as_array();
                                                }
                                            }
                                        }
                                    }
                                    $output = $contact->as_array();
                                    if (!empty($contactNotes)) {
                                        $output['notes'] = $contactNotes;
                                    }
                                    echo json_encode(array('Result' => "OK"));
                                    return;
                                 // tenho q por essa resposta pro jquery
                                    echo json_encode($output,JSON_PRETTY_PRINT);
                                    return;
                                } else {
                                    throw new Exception("Unable to save contact");
                                }
                            } else {
                                #echo "<pre>";
                                #print_r($errors['contact']);
                                #die;
                                $a= implode('::',$errors['contact'][0]);
                                throw new ValidationException($a);
                                #throw new ValidationException("Invalid data",0,$errors['contact'][0]);
                            }
                        }
                        $app->notFound();
                    }
                )->via('PUT', 'PATCH');
                // Delete contact with ID
                $app->delete('/contacts/:id',function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->find_one($id);
                        if ($contact) {
                            $contact->delete();
                            #$app->halt(204);
                            #$app->halt(200,"OK");
                            echo json_encode(array('Result' => "OK"));
                            die();
                            #echo json_encode($contacts, JSON_PRETTY_PRINT);
                        }
                        $app->notFound();
                    }
                );
                #########################################################################
                ############################# alterando apenas um pedaco -- editionline
                $app->map('/contacts/:id', function ($id) use ($app, $log) {
                    $contact = \ORM::for_table('contacts')->find_one($id);
                    if ($contact) {
                        parse_str($app->request()->getBody(), $body);
                        $contact->set('firstname', $body['firstname']);
                        if ($contact->save()) {
                            $output = $contact->as_array();
                            echo json_encode(array('Result' => "OK"));
                            return;
                        } else {
                            throw new Exception("Unable to save contact");
                        }
                    }
                    $app->notFound();
                    }
                )->via('PUT', 'PATCH');
                #########################################################################
                #########################################################################
                

                // Add contact to favorites
                $app->put('/contacts/:id/star', function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->find_one($id);
                        if ($contact) {
                            $contact->set('favorite', 1);
                            if (true === $contact->save()) {
                                $output = $contact->as_array();
                                echo json_encode($output,JSON_PRETTY_PRINT);
                                return;
                            } else {
                                throw new Exception("Unable to save contact");
                            }
                        }
                        $app->notFound();
                    }
                );
                // Remove contact from favorites
                $app->delete('/contacts/:id/star',function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->find_one($id);
                        if ($contact) {
                            $contact->set('favorite', 0);
                            if (true === $contact->save()) {
                                $output = $contact->as_array();
                                echo json_encode($output,JSON_PRETTY_PRINT);
                                return;
                            } else {
                                throw new Exception("Unable to save contact");
                            }
                        }
                        $app->notFound();
                    }
                );
                // Get notes for contact
                $app->get('/contacts/:id/notes', function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->select('id')->find_one($id);
                        if ($contact) {
                            $notes = \ORM::for_table('notes')->where('contact_id', $id)->find_array();
                            echo json_encode($notes, JSON_PRETTY_PRINT);
                            return;
                        }
                        $app->notFound();
                    }
                );
                // Add a new note for contact with id :id
                $app->post('/contacts/:id/notes', function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->select('id')->find_one($id);
                        if ($contact) {
                            parse_str($app->request()->getBody(), $body);
                            #$body = $app->request()->getBody();
                            $errors = $app->validateNote($body);
                            if (empty($errors)) {
                                $note = \ORM::for_table('notes')->create();
                                $note->set($body);
                                $note->contact_id = $id;
                                if (true === $note->save()) {
                                    $jt = array();
                                    $jt['Result'] = "OK";
                                    $jt['Record'] = (array) $note->as_array();
                                    echo json_encode($jt);
                                    #echo json_encode($note->as_array(),JSON_PRETTY_PRINT);
                                    return;
                                } else {
                                    throw new Exception("Unable to save note");
                                }
                            } else {
                                throw new ValidationException("Invalid data",0,$errors);
                            }
                        }
                        $app->notFound();
                    }
                );
                // Get single note
                $app->get('/contacts/:id/notes/:note_id',function ($id, $note_id) use ($app, $log) {
                        $id = filter_var( filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
                        if (false === $id) {
                            throw new ValidationException("Invalid contact ID");
                        }
                        $note_id = filter_var(filter_var($note_id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
                        if (false === $note_id) {
                            throw new ValidationException("Invalid note ID");
                        }
                        $contact = \ORM::for_table('contacts')->select('id')->find_one($id);
                        if ($contact) {
                            $note = \ORM::for_table('notes')->find_one($note_id);
                            
                            if ($note) {
                                echo json_encode($note->as_array(),JSON_PRETTY_PRINT);
                                return;
                            }
                        }
                        $app->notFound();
                    }
                );
                // Update a single note
                $app->map('/contacts/:id/notes/:note_id', function ($id, $note_id) use ($app, $log) {
                        $id = filter_var( filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
                        if (false === $id) {
                            throw new ValidationException("Invalid contact ID");
                        }
                        $note_id = filter_var( filter_var($note_id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
                        if (false === $note_id) {
                            throw new ValidationException("Invalid note ID");
                        }
                        $contact = \ORM::for_table('contacts')->select('id')->find_one($id);
                        if ($contact) {
                            $note = \ORM::for_table('notes')->find_one($note_id);
                            if ($note) {
                                #$body = $app->request()->getBody();
                                parse_str($app->request()->getBody(), $body);
                                $errors = $app->validateNote($body, 'update');
                                if (empty($errors)) {
                                    $note->set('body', $body['body']);
                                    if (true === $note->save()) {
                                        echo json_encode(array('Result' => "OK"));
                                        #echo json_encode($note->as_array(),JSON_PRETTY_PRINT);
                                        return;
                                    } else {
                                        throw new Exception("Unable to save note");
                                    }
                                } else {
                                    throw new ValidationException("Invalid data",0,$errors);
                                }
                            }
                        }
                        $app->notFound();
                    }
                )->via('PUT', 'PATCH');
                // Delete single note
                $app->delete('/contacts/:id/notes/:note_id', function ($id, $note_id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->select('id')->find_one($id);
                        if ($contact) {
                            $note = \ORM::for_table('notes')->find_one($note_id);
                            if ($note) {
                                $note->delete();
                                echo json_encode(array('Result' => "OK"));
                                return;
                                $app->halt(204);
                            }
                        }
                        $app->notFound();
                    }
                );
            }
        );
    }
);
// Public human readable home page
$app->get('/', function () use ($app, $log) {
        echo "<h1>Hello, this can be the public App Interface</h1>";
    }
);
// JSON friendly errors
// NOTE: debug must be false
// or default error template will be printed
$app->error(function (Exception $e) use ($app, $log) {

    echo "jjjjjjjjjjjjjjjj";die;
    $mediaType = $app->request->getMediaType();
    $isAPI = (bool) preg_match('|^/api/v.*$|', $app->request->getPath());
    // Standard exception data
    $error = array(
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    );
    // Graceful error data for production mode
    if (!in_array(get_class($e),array('API\\Exception', 'API\\Exception\ValidationException')) 
                                                    && 'production' === $app->config('mode')) {
        $error['message'] = 'There was an internal error';
        unset($error['file'], $error['line']);
    }
    // Custom error data (e.g. Validations)
    if (method_exists($e, 'getData')) {
        $errors = $e->getData();
    }
    if (!empty($errors)) {
        $error['errors'] = $errors;
    }
    $log->error($e->getMessage());

    $message = implode('<br>',$error);
    echo $message; die;

    $app->response->headers->set('Content-Type','application/json');
    echo json_encode(array('Result' => "ERROR", 'Message - getData():' => $message));
    #die('erro fdfdfdfddfdfddfdf');
    #return;

    if ('application/json' === $mediaType || true === $isAPI) {
        $app->response->headers->set('Content-Type','application/json');
        echo json_encode(array('Result' => "ERROR", 'Message' => "aaaaaaaaaaaaaaaa"));
        echo json_encode($error, JSON_PRETTY_PRINT);
    } else {
        $app->response->headers->set('Content-Type','application/json');
        echo json_encode(array('Result' => "ERROR", 'Message' => "bbbbbbbbbbbbbbb"));
        echo json_encode($error, JSON_PRETTY_PRINT);
#        echo '<html><head><title>Error</title></head><body><h1>Error:'.$error['code']. '</h1><p>'. $error['message'].'</p></body></html>';
    }
});
/// Custom 404 error
$app->notFound(function () use ($app) {
    $mediaType = $app->request->getMediaType();
    $isAPI = (bool) preg_match('|^/api/v.*$|', $app->request->getPath());
    if ('application/json' === $mediaType || true === $isAPI) {
        $app->response->headers->set('Content-Type','application/json');
        echo json_encode(array('Result' => "ERROR", 'Message' => "Pag Not Found"));
        #echo json_encode(array('code' => 404,'message' => 'Not found'),JSON_PRETTY_PRINT);
    } else {
        $app->response->headers->set('Content-Type','application/json');
        # ele ta entrando aqui
        echo json_encode(array('Result' => "ERROR", 'Message' => "404 aloo oiii Not Found"));
        #echo json_encode(array('code' => 404,'message' => 'Not found'),JSON_PRETTY_PRINT);
        #echo '<html><head><title>404</title></head><body><h1>404 Page NF</h1></body></html>';
    }
});

try{
    $app->run();    
} catch(ValidationException $e){
    echo json_encode(array('Result' => "ERROR", 'Message' => "errro 2222222"));
    #echo json_encode($e->getData(), JSON_PRETTY_PRINT);

} catch(\PDOException $e){
    echo json_encode(array('Result' => "ERROR", 'Message' => "errro 33333333333"));
    #echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
} catch(ErrorException $e){
    echo json_encode(array('Result' => "ERROR", 'Message' => "errro 33333333333"));
    #echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
} catch(Exception $e){
    echo json_encode(array('Result' => "ERROR", 'Message' => "errro 33333333333"));
    #echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
}
