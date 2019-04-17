<?php  
if (!defined('_VALID_MOS') && !defined('_JEXEC'))
	die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

if (!class_exists('hikashopPaymentPlugin'))
	require(dirname(__FILE__) . DS.'hikashopPaymentPlugin.php');
  
  

include_once($_SERVER['DOCUMENT_ROOT'].'/administrator/components/com_hikashop/helpers/helper.php');
hikashop_config();


                                               
class plgHikashoppaymentcloudkassir extends hikashopPaymentPlugin
{
	
	public $methodId;

	var $multiple = true;
	var $name = 'cloudkassir';
	var $doc_form = 'cloudkassir';
	/*var $pluginConfig = array(
		'publishable_key' => array('STRIPE_PUBLISHABLE_KEY', 'input'),
		'secret_key' => array('STRIPE_SECRET_KEY', 'input'),
		'debug' => array('DEBUG', 'boolean', '0'),
		'return_url' => array('RETURN_URL', 'input'),
		'invalid_status' => array('INVALID_STATUS', 'orderstatus'),
		'pending_status' => array('PENDING_STATUS', 'orderstatus'),
		'verified_status' => array('VERIFIED_STATUS', 'orderstatus')
	); */
  

  
  
	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

	}
  
  /** Обработка изменения статуса заказа в админке **/
  public function onBeforeOrderUpdate($order,$methods)         //ok
  {                 
        self::addError("onBeforeOrderUpdate - kassir--");
       // self::addError(print_r($order,1));
       // self::addError(print_r($methods,1));
        global $oDb;
    		$oDb = JFactory::getDBO();
    		$sSql = "SELECT `payment_id` FROM `".hikashop_table('payment')."` where `payment_name`='cloudkassir' LIMIT 1";
    		$oDb->setQuery($sSql);
    		$sRow = $oDb->loadAssocList();
        if ($sRow[0]['payment_id']):

              $this->pluginParams($sRow[0]['payment_id']);
              $this->payment_params =& $this->plugin_params;
              $params=self::Object_to_array($this->payment_params);
              if ($order->order_status==$params['STATUS_SUCCESS']):
                    self::addError("Send kkt Income!");
                    self::SendReceipt($order, 'Income',$params);
              elseif ($order->order_status==$params['STATUS_REFUND']):
                    self::addError("Send kkt IncomeReturn!");
                    self::SendReceipt($order, 'IncomeReturn',$params);
              endif; 
        endif;
      
  }   
  
  

	/** Срабатывает после оформления заказа **/
  public function onAfterOrderConfirm(&$order, &$methods, $method_id)   //ok  ----
  {
                 self::addError("6666666666666666");
		parent::onAfterOrderConfirm($order, $methods, $method_id);
  } 



  public function OrderSetHistory($order_id,$text)   //ok ----
  {
        if ($order_id):
              global $oDb;
          		$oDb = JFactory::getDBO();
          		$sSql = "INSERT INTO `#__hikashop_history` (`history_order_id`, `history_created`,`history_data`) VALUES (".$order_id.", '".date("dmY")."','".$text."')";
          		$oDb->setQuery($sSql);
              $oDb->query(); 
        endif;
  }

  
  
  public function get_cart($order)
  {
      global $oDb;
      //$order->order_id
      if ($order->order_id):
              $oDb = JFactory::getDBO();
              $query="SELECT `product_id` FROM `".hikashop_table('order_product')."` where `order_id`=".$order->order_id;
              self::addError(print_r($query,1));
        			$oDb->setQuery($query);
        			$rows0 = $oDb->loadObjectList();
        			foreach($rows0 as $k => $row0)
              {
                 $product_ids[]=$row0->product_id;
              }
              self::addError(print_r($product_ids,1));
         			$query = 'SELECT * FROM '.hikashop_table('product').' WHERE product_id IN ('.implode(',',$product_ids).')';
        			$oDb->setQuery($query);
        			$rows = $oDb->loadObjectList(); 
          		$element = array();
              $currencyClass = hikashop_get('class.currency');
        			$currency_id = $order->order_currency_id;
              $config =& hikashop_config();
              $main_currency = (int)$config->get('main_currency',1);
              $zone_id = hikashop_getZone(null);
              $discount_before_tax = (int)$config->get('discount_before_tax',0);
              $user_id = $order->order_user_id;
              $currencyClass->getPrices($rows, $product_ids, $currency_id, $main_currency, $zone_id, $discount_before_tax, $user_id);

          		if(!empty($rows))
              {
          			foreach($rows as $k => $row)
                {
          				$obj = new stdClass();
          				$obj->order_product_name = $row->product_name;
          				$obj->order_product_code = $row->product_code;
          				$obj->order_product_quantity = (!empty($quantities[$row->product_id]) ? $quantities[$row->product_id]:1 );
          				$currencyClass->pricesSelection($row->prices,$obj->order_product_quantity);
          				$obj->product_id = $row->product_id;
                  self::addError('SELECT * FROM '.hikashop_table('order_product').' WHERE `order_id`='.$order->order_id.' and `product_id`='.$obj->product_id);
             			$query2 = 'SELECT * FROM '.hikashop_table('order_product').' WHERE `order_id`='.$order->order_id.' and `product_id`='.$obj->product_id;
            			$oDb->setQuery($query2);
            			$rows2 = $oDb->loadObjectList();
          				$obj->order_id = (int)$data->order_id;
                  self::addError("PRICE__________");
                 // self::addError(print_r($rows2,1));
          			/*	if(!empty($row->prices))
                  {
          					foreach($row->prices as $price)
                    {
          						$obj->order_product_price = $price->price_value;
          						$obj->order_product_tax = ($price->price_value_with_tax-$price->price_value);
          						$obj->order_product_tax_info = $price->taxes;
          					}
          				}
                  else  */
                  $obj->order_product_price=$rows2[0]->order_product_price;
          				$element[$k]=$obj;
          			}
          		}
              
              return $element;
      endif;
  }          
 
  public function send_request($API_URL,$params,$request)           ///ok
  {
          if($curl = curl_init()):
                self::addError("send_request");

                // $request['CustomerReceipt']['Items'][0]['price']=1;
               // $request['CustomerReceipt']['Items'][0]['amount']=1;
                self::addError(print_r($request,1));   
                
                $request2=self::cur_json_encode($request);
                
                $str=date("d-m-Y H:i:s").$request['Type'].$request['InvoiceId'].$request['AccountId'].$request['CustomerReceipt']['email'];
                $reque=md5($str);
                $ch = curl_init($API_URL);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch,CURLOPT_USERPWD,$params['PublicID'] . ":" . $params['APIPASS']);
                curl_setopt($ch, CURLOPT_URL, $API_URL);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Request-ID:".$reque));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);              
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request2);
              	$content = curl_exec($ch);
                self::addError(print_r($content,1));
          	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            		$curlError = curl_error($ch);
            		curl_close($ch);
          endif;
  }
  
         

	private function SendReceipt($order, $sType,$params)
  {
        $cart=self::get_cart($order);
      //  self::addError('""""""""888""""');
     //  self::addError(print_r($order,1));
       //  self::addError(print_r($cart,1));
       // $cart=self::object_to_array($order->cart);
      	foreach($cart as $item):
           
            $items[]=array(
                    'label'=>$item->order_product_name,
                    'price'=>number_format($item->order_product_price,2,".",''),
                    'quantity'=>$item->order_product_quantity,
                    'amount'=>number_format(floatval($item->order_product_price*$item->order_product_quantity),2,".",''),
                    'vat'=>$params['NDS'], ///     $Ar_params['cloudpayments_vat']
            ); 
        endforeach; 
        
        if ($order->old->order_shipping_price):
            $items[]=array(
                    'label'=>"Доставка",
                    'price'=>$order->old->order_shipping_price,
                    'quantity'=>1,
                    'amount'=>$order->old->order_shipping_price,
                    'vat'=>$params['NDS_DELIVERY'], 
            ); 
        endif;
        //self::addError('!!!!!!!!!1!!!!!!!!!');
        $Ar_params['phone']='';
        $Ar_params['email']='';
        //self::addError(print_r($order,1));
        //self::addError('!!!!!!!!!2!!!!!!!!!'); 
        if ($order->order_user_id) $order_user_id=$order->order_user_id;
        else if($order->old->order_user_id) $order_user_id=$order->old->order_user_id;
             
        if ($order_user_id):
                global $oDb;
            		$oDb = JFactory::getDBO();
            		$sSql = "SELECT `address_telephone` FROM ".hikashop_table("address")." where `address_user_id`=".$order_user_id;
            		$oDb->setQuery($sSql);
            		$sRow = $oDb->loadAssocList();
                $Ar_params['phone']=$sRow[0]['address_telephone'];
                
            		$sSql = "SELECT `user_email` FROM ".hikashop_table("user")." where `user_id`=".$order_user_id;
            		$oDb->setQuery($sSql);
            		$sRow = $oDb->loadAssocList();
                self::addError(print_r($sRow,1));
                //print_r($sRow[0]['user_email']); die();
                $Ar_params['email']=$sRow[0]['user_email'];
        endif;


        $data['cloudPayments']['customerReceipt']['Items']=$items;
        $data['cloudPayments']['customerReceipt']['taxationSystem']=$params['TYPE_NALOG']; ///
        $data['cloudPayments']['customerReceipt']['calculationPlace']=$params['calculationPlace'];
        $data['cloudPayments']['customerReceipt']['email']=$Ar_params['email']; 
        $data['cloudPayments']['customerReceipt']['phone']=$Ar_params['phone'];  
        
      
        
    		$aData = array(
    			'Inn' => $params['INN'],
    			'InvoiceId' => $order->order_id, //номер заказа, необязательный
    			'AccountId' => $order_user_id,
    			'Type' => $sType,
    			'CustomerReceipt' => $data['cloudPayments']['customerReceipt']
    		);
        $API_URL='https://api.cloudpayments.ru/kkt/receipt';
        self::send_request($API_URL,$params,$aData);
        self::addError("kkt/receipt");
	}


  public function get_order($request)   ///OK   ----
  {
      global $oDb;
      $this->addError('check_payment');
  		$oDb = JFactory::getDBO();
  		$sSql = "SELECT * FROM `#__hikashop_order` where  `order_id`=".$request['InvoiceId']." LIMIT 1";
  		$oDb->setQuery($sSql);
  		$sRow = $oDb->loadAssocList();
      if ($sRow[0]):
          return $sRow[0]; 
      else: return false;
      endif;
  }


  public function onPaymentNotification(&$statuses)  //ok ----
  {  
        self::addError('onPaymentNotification');
        self::addError(print_r($_GET,1));
        self::addError(print_r($_POST,1));
    		if ($_GET['action']=='cloudpayments_receipt')
        {
          		if ($_GET['notif_payment']):
                  global $oDb;
              		$oDb = JFactory::getDBO();
              	  $sSql = "SELECT `payment_id` FROM `#__hikashop_payment` where `payment_name`='cloudkassir' LIMIT 1";
              		$oDb->setQuery($sSql);
              		$sRow = $oDb->loadAssocList();
                  if ($sRow[0]):
                        $method_id=$sRow[0]['payment_id'];
                    		$this->pluginParams($method_id);
                    		$this->payment_params =& $this->plugin_params;
                        $params=self::Object_to_array($this->payment_params); 
                        $accesskey=trim($params['APIPASS']);
                        if($this->CheckHMac($accesskey)):
                              $sType = $_POST['Type'];
                        			if ($sType != 'Income' && $sType != 'IncomeReturn'):
                                  exit('{"error":"unknown receipt type"}');
                              else:
                                  $order=self::get_order($_POST);
                                  self::OrderSetHistory($order->order_id,'Чек отправлен №'.$_POST['DocumentNumber'].' от '.$_POST['DateTime']);
                                  exit('{"code":0}');
                              endif;      
                        endif;
                  endif;
              endif;
    		}
  }
  



    function get_params($param=false)   ///OK   ---
    {
      if ($param) return $this->payment_params->$param;
      else return $this->payment_params;
    }


                                    
    function Object_to_array($data)      //ok     ----
    {
        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = self::Object_to_array($value);
            }
            return $result;
        }
        return $data;
    }
    
    private function CheckHMac($APIPASS)   //ok   ---
    {

        $headers = $this->detallheaders();      
        $this->addError(print_r($headers,true));        
                        
        if (isset($headers['Content-HMAC']) || isset($headers['Content-Hmac'])) 
        {
            $this->addError('HMAC_1');
            $this->addError($APIPASS);
            $message = file_get_contents('php://input');
            $s = hash_hmac('sha256', $message, $APIPASS, true);
            $hmac = base64_encode($s); 
            
            $this->addError(print_r($hmac,true));
            if ($headers['Content-HMAC']==$hmac) return true;
            else if($headers['Content-Hmac']==$hmac) return true;                                    
        }
        else return false;
    }
    
    


	private function extractDataFromRequest($request)     ///
	{
		return array(
			'HEAD' => $request->get('action').'Response',
			'INVOICE_ID' =>  $request->get('InvoiceId')
		);
	}


  function cur_json_encode($a=false)      /////ok
  {
      if (is_null($a) || is_resource($a)) {
          return 'null';
      }
      if ($a === false) {
          return 'false';
      }
      if ($a === true) {
          return 'true';
      }
      
      if (is_scalar($a)) {
          if (is_float($a)) {
              //Always use "." for floats.
              $a = str_replace(',', '.', strval($a));
          }
  
          // All scalars are converted to strings to avoid indeterminism.
          // PHP's "1" and 1 are equal for all PHP operators, but
          // JS's "1" and 1 are not. So if we pass "1" or 1 from the PHP backend,
          // we should get the same result in the JS frontend (string).
          // Character replacements for JSON.
          static $jsonReplaces = array(
              array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
              array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
          );
  
          return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
  
      $isList = true;
  
      for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
          if (key($a) !== $i) {
              $isList = false;
              break;
          }
      }
  
      $result = array();
      
      if ($isList) {
          foreach ($a as $v) {
              $result[] = self::cur_json_encode($v);
          }
      
          return '[ ' . join(', ', $result) . ' ]';
      } else {
          foreach ($a as $k => $v) {
              $result[] = self::cur_json_encode($k) . ': ' . self::cur_json_encode($v);
          }
  
          return '{ ' . join(', ', $result) . ' }';
      }
  }



   

  
  public function addError($text)              ///OK   ----
  {
        $debug=false;
        if ($debug)
        {
          $file=$_SERVER['DOCUMENT_ROOT'].'/plugins/hikashoppayment/cloudkassir/log.txt';          
          $current = file_get_contents($file);
          $current .= date("d-m-Y H:i:s").":".$text."\n";
          file_put_contents($file, $current);
        }
  }
  
  /////////////////////////////////////////////////////////////

	
//throw new Exception

}