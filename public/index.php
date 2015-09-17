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

// Public human readable home page
$app->get('/', function () use ($app, $log) {
        echo "<h1>Hello, this can be the public App Interface</h1>";
    }
);

#http://localhost/modelo/public/
$app->get('/', function () use ($app, $log) {
    $app->render('header.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
    $app->render('home.php',array('home' => 'Entrada Raiz'),200);
    $app->render('footer.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
});


$app->group('/api',function () use ($app, $log) {
        $app->get('/', function () use ($app, $log) {
            $app->render('header.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
            $app->render('home.php',array('home' => 'Entrada API'),200);
            $app->render('footer.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
        });

        #$app->group('/v1', $authenticateForRole('admin') , function () use ($app, $log) {
        $app->group('/v1', function () use ($app, $log) {
            $app->get('/', function () use ($app, $log) {
                $app->render('header.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
                $app->render('home.php',array('home' => 'entrada V1'),200);
                $app->render('footer.php',array('title' => 'aaaaaaaaaaaaaaagdfgdfgdfg'));
            });

            
            $app->get('/pedidos', function () use ($app, $log) {
                $contacts = array();
                $results = \ORM::for_table('pedidos');
                $contacts = $results->find_array();
                //======================== adding cli =========================
                foreach($contacts as $k=>$v){
                    foreach($v as $key=>$val){
                        if($key == 'cli_id'){
                            $id = $val;
                            $categ = \ORM::for_table('clientes')->find_one($id)->as_array();
                            if (!empty($categ)) {
                                $contacts[$k]['cliente'] = $categ;
                            }
                        }
                    }
                }
                //======================== adding itens =========================
                foreach($contacts as $k=>$v){
                    foreach($v as $key=>$val){
                        if($key == 'id'){
                            $id = $val;
                            $notes = \ORM::for_table('itens')->where('ped_id', $id)->order_by_desc('id')->find_array();
                            if (!empty($notes)) {
                                $contacts[$k]['itens'] = $notes;
                            }
                        }
                    }
                }
                $response = $app->response();
                $response['Content-Type'] = 'application/json';
                $response->status(200);
                $response->body(json_encode($contacts,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            });
                    


                $app->get('/contacts', function () use ($app, $log) {
                        $contacts = array();
                        $filters = array();
                        $total = 0;
                        $pages = 1;
                        $results = \ORM::for_table('contacts');

                        if ($rawfilters = $app->request->get()) {
                            unset(
                                #$rawfilters['sort'],
                                #$rawfilters['fields'],
                                #$rawfilters['page'],
                                #$rawfilters['per_page'],
                                ######################## bootstrap-table
                                $rawfilters['order'],
                                $rawfilters['limit'],
                                $rawfilters['offset']

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
                                $fields);
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
                                $sort);

                            foreach ($sort as $expr) {
                                if (substr($expr, 0, 1)=='-') {
                                    $results->order_by_desc(substr($expr, 1));
                                } else {
                                    $results->order_by_asc($expr);
                                }
                            }
                        }
                        $limit = filter_var($app->request->get('limit'),FILTER_SANITIZE_NUMBER_INT) ?: "25";
                        $offset = filter_var($app->request->get('offset'),FILTER_SANITIZE_NUMBER_INT) ?: "5";

                        $results->limit($limit)->offset($offset);

                        $contacts = $results->find_array();

                        //======================== adding categ =========================
                        foreach($contacts as $k=>$v){
                            foreach($v as $key=>$val){
                                if($key == 'categ_id'){
                                    $id = $val;
                                    $categ = \ORM::for_table('categorias')->find_one($id)->as_array();
                                    if (!empty($categ)) {
                                        $contacts[$k]['categ'] = $categ;
                                    }
                                    
                                }
                            }
                        }

                        //======================== adding notes =========================
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
                              

                        if (empty($total)) {
                            $total = count($contacts);
                        }
                        $app->response->headers->set('X-Total-Count', $total);

                        $response = $app->response();
                        $response['Content-Type'] = 'application/json';
                        $response->status(200);
                        $response->body(json_encode($contacts,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                    }
                );
                // Get contact with ID
                $app->get('/contacts/:id', function ($id) use ($app, $log) {
                        $id = filter_var( filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
                        if (!$id) {
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

                        parse_str($app->request()->getBody(), $body);

                        if(empty($body)){
                            throw new Exception("Sem dados de post");
                        }
                        $errors = $app->validateContact($body);

                        if (empty($errors)) {
                            $contact = \ORM::for_table('contacts')->create();
                            if (isset($body['notes'])) {
                                $notes = $body['notes'];
                                unset($body['notes']);
                            }
                            $contact->set($body);
                            if ($contact->save()){
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
                                $jt['result'] = "OK";
                                $jt['contact'] = (array) $output;
                                echo json_encode($jt,JSON_PRETTY_PRINT);
                                die(); // tenho q por essa resposta pro jquery
                            } else { #use contact->save() for falso
                                throw new Exception("Unable to save contact");
                            }
                        } else {
                            $errors['result']='error';
                            echo json_encode($errors, JSON_PRETTY_PRINT);
                            die;
                            throw new ValidationException("POST - Invalid data",0,$errors);
                        }
                    }
                );

                // Update contact with ID
                $app->map('/contacts/:id', function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->find_one($id);
                        if ($contact) {
                            parse_str($app->request()->getBody(), $body);
                            $body['id'] = (isset($body['id']) && is_numeric($body['id']))? $body['id'] : $id;
                            $errors = $app->validateContact($body, 'update');
                            if (empty($errors)) {
                                if (isset($body['notes'])) {
                                    $notes = $body['notes'];
                                    unset($body['notes']);
                                }
                                $contact->set($body);
                                if ($contact->save()) {
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
                                                if ($note->save()) {
                                                    $contactNotes[] = $note->as_array();
                                                }
                                            }
                                        }
                                    }
                                    $output = $contact->as_array();
                                    if (!empty($contactNotes)) {
                                        $output['notes'] = $contactNotes;
                                    }
                                    echo json_encode(array('result' => "OK"),JSON_PRETTY_PRINT);
                                    die;
                                 // tenho q por essa resposta pro jquery
                                    echo json_encode($output,JSON_PRETTY_PRINT);
                                    die;
                                } else {
                                    throw new Exception("Unable to save contact");
                                }
                            } else {
                                $errors['result']='error';
                                echo json_encode($errors, JSON_PRETTY_PRINT);
                                die;
                                #throw new ValidationException($a);
                            }
                        }
                        $app->notFound();
                    }
                )->via('PUT');

                // pedacos com PATCHHHHHHHHHHHHHH
                $app->map('/contacts/:id/:campo', function ($id,$campo) use ($app, $log) {
                    $contact = \ORM::for_table('contacts')->find_one($id);
                    if ($contact) {
                        $body = json_decode($app->request()->getBody(),true);
                        #parse_str($app->request()->getBody(), $body);
                        $body['id'] = (isset($body['id']) && is_numeric($body['id']))? $body['id'] : $id;

                        $errors = [];
                        if(!$body){
                            $errors['url']='campo faltante no body';
                            #$errors['campo']='valor invalido';
                        }
                        switch($campo){
                            case 'email':
                            $errors = $app->validaEmail($body);
                            if($errors){ #se tem errors ... true
                                $errors['result']='error';
                                echo json_encode($errors,JSON_PRETTY_PRINT);
                                die;
                            }
                            break;
                        }

                        $contact->set($body);
                        if ($contact->save()) {
                            $output = $contact->as_array();
                            echo json_encode(array('result' => "OK"),JSON_PRETTY_PRINT);
                            die;
                        } else {
                            throw new Exception("Unable to save contact");
                        }
                    } else {
                        #nao achoi o id
                        $app->notFound();
                    }
                })->via('PATCH','PUT');

                // Delete contact with ID
                $app->delete('/contacts/:id',function ($id) use ($app, $log) {
                        $contact = \ORM::for_table('contacts')->find_one($id);
                        if ($contact) {
                            $contact->delete();
                            #$app->halt(204);
                            #$app->halt(200,"OK");
                            echo json_encode(array('Result' => "OK"), JSON_PRETTY_PRINT);
                            die();
                        }
                        $app->notFound();
                    }
                );
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

                            $response = $app->response();
                            $response['Content-Type'] = 'application/json';
                            $response->status(200);
                            $response->body(json_encode($notes,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

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
                                    $jt['result'] = "OK";
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
                        if (!$id) {
                            throw new ValidationException("Invalid contact ID");
                        }
                        $note_id = filter_var(filter_var($note_id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
                        if (!$note_id) {
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
                                    if ($note->save()) {
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
                                echo json_encode(array('result' => "OK"));
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

$app->hook('slim.before.dispatch', function () use ($app) {
    #$app->render('header.php',array('title' => 'Contacts Page'));
});
  
$app->hook('slim.after.dispatch', function () use ($app) {
    #$app->render('footer.php',array('title' => 'aaaaaaadfgdfg'));
});


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
    if (!in_array(get_class($e),array('API\\Exception', 'API\\Exception\ValidationException')) && 'production' === $app->config('mode')) {
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
    echo json_encode(array('result' => "ERROR", 'Message - getData():' => $message));
    #die('erro fdfdfdfddfdfddfdf');
    #return;

    if ('application/json' === $mediaType || true === $isAPI) {
        $app->response->headers->set('Content-Type','application/json');
        echo json_encode(array('result' => "ERROR", 'Message' => "aaaaaaaaaaaaaaaa"));
        echo json_encode($error, JSON_PRETTY_PRINT);
    } else {
        $app->response->headers->set('Content-Type','application/json');
        echo json_encode(array('result' => "ERROR", 'Message' => "bbbbbbbbbbbbbbb"));
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
    echo json_encode(array('result' => "ERROR", 'Message' => "errro 2222222"));
    #echo json_encode($e->getData(), JSON_PRETTY_PRINT);
} catch(\PDOException $e){
    echo json_encode(array('result' => "ERROR", 'Message' => "errro 33333333333"));
    #echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
} catch(ErrorException $e){
    echo json_encode(array('result' => "ERROR", 'Message' => "errro 33333333333"));
    #echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
} catch(Exception $e){
    echo json_encode(array('result' => "ERROR", 'Message' => "errro 33333333333"));
    #echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
}
