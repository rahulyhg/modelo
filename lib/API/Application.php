<?php
namespace API;
use Slim\Slim;

class Application extends Slim{
    protected $errors=array();
    protected function addError($categ,$f,$msg){
        $this->errors[$categ][] = array($f=>$msg);
    }
    protected function getErrors(){
        return $this->errors;
    }
    protected function clear(){
        unset($this->errors);
    }
    protected function hasErrors(){
        if(!empty($this->getErrors())){
            return $this->getErrors();
        }
        return false; #aqui eu retorno false pq la no index-> if(!(errors))
    }
    public function validaEmail($contact){
        #echo "<pre>contact";
        #print_r($contact['email']);

        $email = $contact['email'];
        if (empty($contact['email'])) {
            $this->addError('contact','email','Email address cannot be empty');
        }


        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            #echo "eeeeee:".$email;
            $this->addError('contact','email','Email address is invalid');
        }
        $results = \ORM::for_table('contacts')->where('email', $contact['email'])->count();
        if ($results > 0) {
            $this->addError('contact','email','Email address already exists');
        }
        return $this->hasErrors();
    }
    public function validaFirstName($contact){
        if (empty($contact['firstname'])) {
            $this->addError('contact','firstname','First name cannot be empty');
        }
        return $this->hasErrors();
    }

    public function validaID($contact){
        if (empty($contact['id'])) {
            $this->addError('contact','id','ID cannot be empty');
        }
        if (!filter_var($contact['id'],FILTER_VALIDATE_INT)) {
            $this->addError('contact','id','ID must be integer');
        }
        return $this->hasErrors();
    }

    public function validateContact($contact = array(), $action = 'create'){
        if (!empty($contact['notes'])) {
            $notes = $contact['notes'];
            unset($contact['notes']);
        }
        $contact = filter_var_array(
            $contact,
            array(
                'id' => FILTER_SANITIZE_NUMBER_INT,
                'firstname' => FILTER_SANITIZE_STRING,
                'lastname' => FILTER_SANITIZE_STRING,
                'email' => FILTER_SANITIZE_EMAIL,
                'phone' => FILTER_SANITIZE_STRING,
            ),
            false
        );
        if($action=='update'){
            $this->validaID($contact);
        }
        $this->validaFirstName($contact);
        $this->validaEmail($contact);

        if (!empty($notes) && is_array($notes)) {
            $noteCount = count($notes);
            for ($i = 0; $i < $noteCount; $i++) {
                $noteErrors = $this->validateNote($notes[$i], $action);
                if (!empty($noteErrors)) {
                    $errors['notes'][] = $noteErrors;
                    unset($noteErrors);
                }
            }
        }
        #return $this->hasErrors();
        return $this->getErrors();
    }
    
    public function validateNote($note = array(), $action = 'create'){
        $errors = array();
        $note = filter_var_array($note,array(
                'id' => FILTER_SANITIZE_NUMBER_INT,
                'body' => FILTER_SANITIZE_STRING,
                'contact_id' => FILTER_SANITIZE_NUMBER_INT,
                ),
                false
            );
        $this->validaNotesBody($note);
        $this->validaNotesContactID($note);

        return $this->getErrors();
    }

    public function validaNotesBody($note){
        if (isset($note['body']) && empty($note['body'])) {
            $this->addError('notes','body','Body cannot be empty');
        }
        return $this->hasErrors();
    }

    public function validaNotesContactID($note){
        if (isset($note['body']) && empty($note['body'])) {
            $this->addError('notes','contact_id','contact_id cannot be empty');
        }

        $results = \ORM::forTable('notes')->where('contact_id', $note['contact_id'])->count();
        if (!$results) {
            $this->addError('notes','contact_id','contact_id dont exists');
        }
        return $this->hasErrors();
    }

}