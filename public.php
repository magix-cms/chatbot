<?php
class plugins_chatbot_public extends frontend_db_webservice
{

    protected $data, $template, $collectionDomain, $modelDomain, $setBuildUrl, $curl, $ws, $header;
    public $id, $retrieve,$collection;

    public function __construct($t = null)
    {
        $this->data = new frontend_model_data($this);
        $this->template = $t ? $t : new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->modelDomain = new frontend_model_domain($this->template);
        $this->setBuildUrl = new http_url();
        $this->curl = new http_curl();
        $this->ws = new frontend_model_webservice();
        $this->header = new http_header();
        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
        if (http_request::isGet('collection')) {
            $this->collection = $formClean->simpleClean($_GET['collection']);
        }
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     * @throws Exception
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function setWsAuthKey(){
        $data = $this->getItems('auth',null,'one',false);
        if($data != null){
            if($data['status_ws'] != '0'){
                return $data['key_ws'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * @param $data
     * @return SimpleXMLElement
     */
    private function setParse($data){
        if($data){
            $xml = simplexml_load_string($data, null, LIBXML_NOCDATA);
            return $xml;
        }
    }

    /**
     * @param $config
     * @return SimpleXMLElement
     * @throws Exception
     */
    public function getRequest($config){
        if(is_array($config)) {
            if (isset($config["collection"])) {
                $setData = $this->setWsAuthKey();
                if($setData){
                    $url = new http_url();
                    $setUrl = $url->getUrl() . '/webservice/' . $config['collection'];

                    if (isset($config["retrieve"])) {
                        $setUrl = $url->getUrl() . '/webservice/catalog/'.$config["retrieve"].'/'.$config["id"];
                    }else{
                        if (isset($config["id"])) {
                            $setUrl = $url->getUrl() . '/webservice/' . $config['collection'] . '/' . $config["id"];
                        }
                    }

                    $data = array(
                        'wsAuthKey' => $setData,
                        'url' => $setUrl,
                        'method' => 'xml',
                        'debug' => false
                    );
                    //print_r($data);
                    $response = $this->curl->setPrepareGet($data);
                    //print_r($data);
                    $xml = $this->setParse($response);

                    return $xml;
                }
            }
        }
    }

    /**
     * @param $config
     * @return array
     */
    public function getConfig($config){
        $retrieve = false;
        $id = false;
        $newData = array();
        switch($config['collection']){
            case 'category':
                $retrieve = 'category';
                $newData['retrieve'] = $retrieve;
                break;
            case 'product':
                $retrieve = 'product';
                $newData['retrieve'] = $retrieve;
                break;

        }
        $newData['collection'] = isset($config['collection']) ? $config['collection'] : 'home';

        if(isset($config['id'])) {
            $newData['id'] = $config['id'];
        }
        return $newData;
    }

    /**
     * @throws Exception
     */
    public function run(){
        if($this->ws->setMethod() === 'GET'){
            if(isset($_GET)){
                $this->header->set_json_headers();
                print json_encode(
                    $this->getRequest($this->getConfig($_GET))
                );
            }
        }
    }

}
?>