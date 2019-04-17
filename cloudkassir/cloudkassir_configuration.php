<?php
/**
 * @package	HikaShop for Joomla!
 * @version	3.4.0
 * @author	hikashop.com
 * @copyright	(C) 2010-2018 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');

function GetLang($string)
{
    $document = & JFactory::getDocument();
    require(dirname(__FILE__) . DS . 'language/'.$document->language.'/lang.php');
    if ($MESS[$string]) return $MESS[$string];
    else return $string;
} 

?>
<tr><td colspan="2"><?echo GetLang('VBCH_CLPAY_SPCP_DDESCR');?></td></tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][PublicID]"><?php
			echo GetLang('SALE_HPS_CLOUDPAYMENT_SHOP_ID');
		?>
    <span style="display:block;font-size:10px;"><?echo GetLang('SALE_HPS_CLOUDPAYMENT_SHOP_ID_DESC');?></span>
    </label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][PublicID]" value="<?php echo $this->escape(@$this->element->payment_params->PublicID); ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][APIPASS]"><?php
			echo GetLang('SALE_HPS_CLOUDPAYMENT_SHOP_KEY');
		?>
    <span style="display:block;font-size:10px;"><?echo GetLang('SALE_HPS_CLOUDPAYMENT_SHOP_KEY_DESC');?></span>
    </label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][APIPASS]" value="<?php echo $this->escape(@$this->element->payment_params->APIPASS); ?>" />
	</td>
</tr>

<tr>
	<td class="key">
		<label for="data[payment][payment_params][INN]"><?php
			echo GetLang('SALE_HPS_CLOUDPAYMENT_INN');
		?>
    <span style="display:block;font-size:10px;"><?echo GetLang('SALE_HPS_CLOUDPAYMENT_INN_DESC');?></span>
    </label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][INN]" value="<?php echo $this->escape(@$this->element->payment_params->INN); ?>" />
	</td>
</tr>

<tr>
	<td class="key">
		<label for="data[payment][payment_params][calculationPlace]"><?php
			echo GetLang('SALE_HPS_CLOUDPAYMENT_calculationPlace');
		?>
    <span style="display:block;font-size:10px;"><?echo GetLang('SALE_HPS_CLOUDPAYMENT_calculationPlace_DESC');?></span>
    </label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][calculationPlace]" value="<?php echo $this->escape(@$this->element->payment_params->calculationPlace); ?>" />
	</td>
</tr>



<tr>
	<td class="key">
		<label for="data[payment][payment_params][STATUS_REFUND]"><?php
			echo GetLang('STATUS_REFUND');
		?></label>
	</td>
	<td>
		<?echo $this->data['order_statuses']->display("data[payment][payment_params][STATUS_REFUND]", @$this->element->payment_params->STATUS_REFUND); ?>
	</td>
</tr>


<tr>
	<td class="key">
		<label for="data[payment][payment_params][STATUS_SUCCESS]"><?php
			echo GetLang('STATUS_SUCCESS');
		?></label>
	</td>
	<td>
		<?echo $this->data['order_statuses']->display("data[payment][payment_params][STATUS_SUCCESS]", @$this->element->payment_params->STATUS_SUCCESS); ?>
	</td>
</tr>



<tr>
	<td class="key">
		<label for="data[payment][payment_params][TYPE_NALOG]"><?php
			echo GetLang('SALE_HPS_CLOUDPAYMENT_TYPE_NALOG');
		?>
    <span style="display:block;font-size:10px;"><?echo GetLang('SALE_HPS_CLOUDPAYMENT_TYPE_NALOG_DESC');?></span>
    </label>
	</td>
	<td>
    <select name="data[payment][payment_params][TYPE_NALOG]">
        <option value=""></option>
        <option <?if ($this->element->payment_params->TYPE_NALOG=='0') echo 'selected';?> value="0" selected=""><?=GetLang('SALE_HPS_NALOG_TYPE_0')?></option>
        <option <?if ($this->element->payment_params->TYPE_NALOG=='1') echo 'selected';?> value="1"><?=GetLang('SALE_HPS_NALOG_TYPE_1')?></option>
        <option <?if ($this->element->payment_params->TYPE_NALOG=='2') echo 'selected';?> value="2"><?=GetLang('SALE_HPS_NALOG_TYPE_2')?></option>
        <option <?if ($this->element->payment_params->TYPE_NALOG=='3') echo 'selected';?> value="3"><?=GetLang('SALE_HPS_NALOG_TYPE_3')?></option>
        <option <?if ($this->element->payment_params->TYPE_NALOG=='4') echo 'selected';?> value="4"><?=GetLang('SALE_HPS_NALOG_TYPE_4')?></option>
        <option <?if ($this->element->payment_params->TYPE_NALOG=='5') echo 'selected';?> value="5"><?=GetLang('SALE_HPS_NALOG_TYPE_5')?></option>
    </select>
	</td>
</tr>



<tr>
	<td class="key">
		<label for="data[payment][payment_params][NDS]"><?php
			echo GetLang('SALE_HPS_CLOUDPAYMENT_NDS');
		?>
    </label>
	</td>
	<td>
   <select name="data[payment][payment_params][NDS]">
        <option <?if ($this->element->payment_params->NDS=='') echo 'selected';?> value=""><?=GetLang('SALE_HPS_NDS_0')?></option>
        <option <?if ($this->element->payment_params->NDS=='20') echo 'selected';?> value="20"><?=GetLang('SALE_HPS_NDS_1')?></option>
        <option <?if ($this->element->payment_params->NDS=='10') echo 'selected';?> value="10"><?=GetLang('SALE_HPS_NDS_2')?></option>
        <option <?if ($this->element->payment_params->NDS=='0') echo 'selected';?> value="0"><?=GetLang('SALE_HPS_NDS_3')?></option>
        <option <?if ($this->element->payment_params->NDS=='110') echo 'selected';?> value="110"><?=GetLang('SALE_HPS_NDS_4')?></option>
        <option <?if ($this->element->payment_params->NDS=='120') echo 'selected';?> value="120"><?=GetLang('SALE_HPS_NDS_5')?></option>
    </select>
	</td>
</tr>


<tr>
	<td class="key">
		<label for="data[payment][payment_params][NDS_DELIVERY]"><?php
			echo GetLang('SALE_HPS_CLOUDPAYMENT_NDS_DELIVERY');
		?>
    </label>
	</td>
	<td>
    <select name="data[payment][payment_params][NDS_DELIVERY]">
        <option <?if ($this->element->payment_params->NDS_DELIVERY=='') echo 'selected';?> value=""><?=GetLang('SALE_HPS_NDS_0')?></option>
        <option <?if ($this->element->payment_params->NDS_DELIVERY=='20') echo 'selected';?> value="20"><?=GetLang('SALE_HPS_NDS_1')?></option>
        <option <?if ($this->element->payment_params->NDS_DELIVERY=='10') echo 'selected';?> value="10"><?=GetLang('SALE_HPS_NDS_2')?></option>
        <option <?if ($this->element->payment_params->NDS_DELIVERY=='0') echo 'selected';?> value="0"><?=GetLang('SALE_HPS_NDS_3')?></option>
        <option <?if ($this->element->payment_params->NDS_DELIVERY=='110') echo 'selected';?> value="110"><?=GetLang('SALE_HPS_NDS_4')?></option>
        <option <?if ($this->element->payment_params->NDS_DELIVERY=='120') echo 'selected';?> value="120"><?=GetLang('SALE_HPS_NDS_5')?></option>
    </select>
	</td>
</tr>