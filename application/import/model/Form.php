<?php
namespace app\import\model;

use think\Model;
use think\Db;


class Form extends Model
{
  /**
   * 查找表字段
   */
  public function getColumns($tableName,$group,$isadmin)
  {
    $query = 'select COLUMN_NAME,column_comment from INFORMATION_SCHEMA.Columns where table_name="' . $tableName . '" and table_schema="hesuan_admin"';
//    var_dump($query);
    // [{COLUMN_NAME: '', column_comment: ''}]
    $columns = Db::query($query);
//    var_dump($columns);


    $arr = [];
    // [{COLUMN_NAME: column_comment}]
    foreach ($columns as $key => $value) {
      $ke=$value['COLUMN_NAME'];
      $val=$value['column_comment'];
      $arr[$ke]=$val;
    }
    if($isadmin=='0'||$isadmin== '2' && $tableName=="hengyin_client_funds"){
        foreach ($arr as $key => $v){
            if ($key=='hand_in_fee'&& $v=='上缴手续费'){
                unset($arr['hand_in_fee']);
                unset($arr['上缴手续费']);
            }elseif ($key=='exchange_margin'&&$v=='交易所保证金'){
                unset($arr['exchange_margin']);
                unset($arr['交易所保证金']);
            }elseif ($key=='retention_fee'&& $v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="hengyin_transaction"){
        foreach ($arr as $key => $v){
            if ($key=='handing_in_service_charge'&& $v=='上缴手续费'){
                unset($arr['handing_in_service_charge']);
                unset($arr['上缴手续费']);
            }elseif ($key=='retention_fee'&&$v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }elseif ($key=='net_profit_and_loss'&& $v=='净盈亏'){
                unset($arr['net_profit_and_loss']);
                unset($arr['净盈亏']);
            }elseif ($key=='service_charge_handed_in_at_present'&&$v=='平今上缴手续费'){
                unset($arr['service_charge_handed_in_at_present']);
                unset($arr['平今上缴手续费']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="sanli_client_funds"){
        foreach ($arr as $key => $v){
            if ($key=='net_profit_and_loss'&& $v=='净盈亏'){
                unset($arr['net_profit_and_loss']);
                unset($arr['净盈亏']);
            }elseif ($key=='hand_in_fee'&&$v=='上缴手续费'){
                unset($arr['hand_in_fee']);
                unset($arr['上缴手续费']);
            }elseif ($key=='retention_fee'&& $v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="sanli_transaction"){
        foreach ($arr as $key => $v){
            if ($key=='sales_department'&& $v=='营业部'){
                unset($arr['sales_department']);
                unset($arr['营业部']);
            }elseif ($key=='customer_classification'&&$v=='客户分类'){
                unset($arr['customer_classification']);
                unset($arr['客户分类']);
            }elseif ($key=='futures_classification'&& $v=='期货分类'){
                unset($arr['futures_classification']);
                unset($arr['期货分类']);
            }elseif ($key=='futures_grouping'&&$v=='期货分组'){
                unset($arr['futures_grouping']);
                unset($arr['期货分组']);
            }elseif ($key=='transaction_time'&&$v=='成交时间'){
                unset($arr['transaction_time']);
                unset($arr['成交时间']);
            }elseif ($key=='handing_in_service_charge'&&$v=='上缴手续费'){
                unset($arr['handing_in_service_charge']);
                unset($arr['上缴手续费']);
            }elseif ($key=='retention_fee'&&$v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }elseif ($key=='option_exercise_opening_amount'&&$v=='期权执行开仓金额'){
                unset($arr['option_exercise_opening_amount']);
                unset($arr['期权执行开仓金额']);
            }elseif ($key=='number_of_options_executed'&&$v=='期权执行手数'){
                unset($arr['number_of_options_executed']);
                unset($arr['期权执行手数']);
            }elseif ($key=='number_of_abandoned_options'&&$v=='期权放弃手数'){
                unset($arr['number_of_abandoned_options']);
                unset($arr['期权放弃手数']);
            }elseif ($key=='number_of_open_positions_under_option_execution'&&$v=='期权执行开仓手数'){
                unset($arr['number_of_open_positions_under_option_execution']);
                unset($arr['期权执行开仓手数']);
            }elseif ($key=='service_charge_handed_in_at_present'&&$v=='平今上缴手续费'){
                unset($arr['service_charge_handed_in_at_present']);
                unset($arr['平今上缴手续费']);
            }elseif ($key=='transaction_code'&&$v=='交易编码'){
                unset($arr['transaction_code']);
                unset($arr['交易编码']);
            }elseif ($key=='floating_profit_and_loss_of_option_closing'&&$v=='期权平仓浮动盈亏'){
                unset($arr['floating_profit_and_loss_of_option_closing']);
                unset($arr['期权平仓浮动盈亏']);
            }elseif ($key=='floating_profit_and_loss_of_option_closing'&&$v=='期权平仓浮动盈亏'){
                unset($arr['floating_profit_and_loss_of_option_closing']);
                unset($arr['期权平仓浮动盈亏']);
            }elseif ($key=='income_royalty'&&$v=='收入权利金'){
                unset($arr['income_royalty']);
                unset($arr['收入权利金']);
            }elseif ($key=='payment_royalty'&&$v=='支出权利金'){
                unset($arr['payment_royalty']);
                unset($arr['支出权利金']);
            }elseif ($key=='royalty'&&$v=='权利金'){
                unset($arr['royalty']);
                unset($arr['权利金']);
            }elseif ($key=='organization_logo'&&$v=='机构标志'){
                unset($arr['organization_logo']);
                unset($arr['机构标志']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="ruida_client_funds"){
        foreach ($arr as $key => $v){
            if ($key=='retention_fee'&& $v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }elseif ($key=='hand_in_fee'&&$v=='上交手续费'){
                unset($arr['hand_in_fee']);
                unset($arr['上交手续费']);
            }elseif ($key=='net_handling_charge'&& $v=='净手续费'){
                unset($arr['net_handling_charge']);
                unset($arr['净手续费']);
            }elseif ($key=='returning_a_servant'&& $v=='返佣'){
                unset($arr['returning_a_servant']);
                unset($arr['返佣']);
            }elseif ($key=='exchange_margin'&& $v=='交易所保证金'){
                unset($arr['exchange_margin']);
                unset($arr['交易所保证金']);
            }elseif ($key=='risk_2'&& $v=='风险度2'){
                unset($arr['risk_2']);
                unset($arr['风险度2']);
            }elseif ($key=='delivery_margin'&& $v=='交割保证金'){
                unset($arr['delivery_margin']);
                unset($arr['交割保证金']);
            }elseif ($key=='exchange_settlement_margin'&& $v=='交易所交割保证金'){
                unset($arr['exchange_settlement_margin']);
                unset($arr['交易所交割保证金']);
            }elseif ($key=='customer_class'&& $v=='客户类别'){
                unset($arr['customer_class']);
                unset($arr['客户类别']);
            }elseif ($key=='customer_class_name'&& $v=='客户类名称'){
                unset($arr['customer_class_name']);
                unset($arr['客户类名称']);
            }elseif ($key=='money_pledge_amount'&& $v=='货币质押金额'){
                unset($arr['money_pledge_amount']);
                unset($arr['货币质押金额']);
            }elseif ($key=='foreign_exchange_in'&& $v=='外汇换入'){
                unset($arr['foreign_exchange_in']);
                unset($arr['外汇换入']);
            }elseif ($key=='foreign_exchange'&& $v=='外汇换出'){
                unset($arr['foreign_exchange']);
                unset($arr['外汇换出']);
            }elseif ($key=='pledge_amount'&& $v=='质押金额'){
                unset($arr['pledge_amount']);
                unset($arr['质押金额']);
            }elseif ($key=='execution_fee'&& $v=='执行手续费'){
                unset($arr['execution_fee']);
                unset($arr['执行手续费']);
            }elseif ($key=='hand_in_the_execution_fee'&& $v=='上交执行手续费'){
                unset($arr['hand_in_the_execution_fee']);
                unset($arr['上交执行手续费']);
            }elseif ($key=='royalty_income_and_expenditure'&& $v=='权利金收支'){
                unset($arr['royalty_income_and_expenditure']);
                unset($arr['权利金收支']);
            }elseif ($key=='operating_profit_and_loss'&& $v=='执行盈亏'){
                unset($arr['operating_profit_and_loss']);
                unset($arr['执行盈亏']);
            }elseif ($key=='market_value_of_options'&& $v=='期权市值'){
                unset($arr['market_value_of_options']);
                unset($arr['期权市值']);
            }elseif ($key=='customer_market_value_equity'&& $v=='客户市值权益'){
                unset($arr['customer_market_value_equity']);
                unset($arr['客户市值权益']);
            }elseif ($key=='delivery_commission'&& $v=='交割手续费'){
                unset($arr['delivery_commission']);
                unset($arr['交割手续费']);
            }elseif ($key=='delivery_service_charge'&& $v=='上交交割手续费'){
                unset($arr['delivery_service_charge']);
                unset($arr['上交交割手续费']);
            }elseif ($key=='total_freeze'&& $v=='总冻结'){
                unset($arr['total_freeze']);
                unset($arr['总冻结']);
            }elseif ($key=='value_added_tax'&& $v=='增值税'){
                unset($arr['value_added_tax']);
                unset($arr['增值税']);
            }elseif ($key=='funds_available_to_exchange_level_clients'&& $v=='交易所级客户可用资金'){
                unset($arr['funds_available_to_exchange_level_clients']);
                unset($arr['交易所级客户可用资金']);
            }elseif ($key=='frozen_amount_of_currency_pledge'&& $v=='货币质押冻结金额'){
                unset($arr['frozen_amount_of_currency_pledge']);
                unset($arr['货币质押冻结金额']);
            }elseif ($key=='risk_freezing_funds_the_next_day'&& $v=='次日风险冻结资金'){
                unset($arr['risk_freezing_funds_the_next_day']);
                unset($arr['次日风险冻结资金']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="ruida_transaction"){
        foreach ($arr as $key => $v){
            if ($key=='transaction_no'&& $v=='成交序号'){
                unset($arr['transaction_no']);
                unset($arr['成交序号']);
            }elseif ($key=='handing_in_the_service_charge'&&$v=='上交手续费'){
                unset($arr['handing_in_the_service_charge']);
                unset($arr['上交手续费']);
            }elseif ($key=='transaction_code'&& $v=='交易编码'){
                unset($arr['transaction_code']);
                unset($arr['交易编码']);
            }elseif ($key=='trader_number'&& $v=='交易员号'){
                unset($arr['trader_number']);
                unset($arr['交易员号']);
            }elseif ($key=='system_number'&& $v=='系统号'){
                unset($arr['system_number']);
                unset($arr['系统号']);
            }elseif ($key=='elegation_type'&& $v=='委托类型'){
                unset($arr['elegation_type']);
                unset($arr['委托类型']);
            }elseif ($key=='specified_price'&& $v=='指定价格'){
                unset($arr['specified_price']);
                unset($arr['指定价格']);
            }elseif ($key=='currency'&& $v=='币种'){
                unset($arr['currency']);
                unset($arr['币种']);
            }elseif ($key=='transaction_number_of_the_exchange'&& $v=='交易所成交号'){
                unset($arr['transaction_number_of_the_exchange']);
                unset($arr['交易所成交号']);
            }elseif ($key=='quantity_per_hand'&& $v=='每手数量'){
                unset($arr['quantity_per_hand']);
                unset($arr['每手数量']);
            }elseif ($key=='mark_of_current_cost_calculation'&& $v=='平今计算费用标志'){
                unset($arr['mark_of_current_cost_calculation']);
                unset($arr['平今计算费用标志']);
            }elseif ($key=='surcharge_1'&& $v=='附加费1'){
                unset($arr['surcharge_1']);
                unset($arr['附加费1']);
            }elseif ($key=='surcharge_2'&& $v=='附加费2'){
                unset($arr['surcharge_2']);
                unset($arr['附加费2']);
            }elseif ($key=='other_charges'&& $v=='其它收费'){
                unset($arr['other_charges']);
                unset($arr['其它收费']);
            }elseif ($key=='strong_flat_sign'&& $v=='强平标志'){
                unset($arr['strong_flat_sign']);
                unset($arr['强平标志']);
            }elseif ($key=='product_identification'&& $v=='产品标识'){
                unset($arr['product_identification']);
                unset($arr['产品标识']);
            }elseif ($key=='single_channel'&& $v=='下单通道'){
                unset($arr['single_channel']);
                unset($arr['下单通道']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="ruida_deposit_and_withdrawal"){
        foreach ($arr as $key => $v){
            if ($key=='serial_number'&& $v=='流水号'){
                unset($arr['serial_number']);
                unset($arr['流水号']);
            }elseif ($key=='type_of_deposit_and_withdrawal'&&$v=='出入金类型'){
                unset($arr['type_of_deposit_and_withdrawal']);
                unset($arr['出入金类型']);
            }elseif ($key=='fund_type'&& $v=='资金类型'){
                unset($arr['fund_type']);
                unset($arr['资金类型']);
            }elseif ($key=='bank'&& $v=='银行'){
                unset($arr['bank']);
                unset($arr['银行']);
            }elseif ($key=='abstract'&& $v=='摘要'){
                unset($arr['abstract']);
                unset($arr['摘要']);
            }elseif ($key=='customer_class'&& $v=='客户类'){
                unset($arr['customer_class']);
                unset($arr['客户类']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="huaxin_client_funds"){
        foreach ($arr as $key => $v){
            if ($key=='settlement_no'&& $v=='结算编号'){
                unset($arr['settlement_no']);
                unset($arr['结算编号']);
            }elseif ($key=='currency_code'&&$v=='币种代码'){
                unset($arr['currency_code']);
                unset($arr['币种代码']);
            }elseif ($key=='organization_code'&& $v=='组织架构代码'){
                unset($arr['organization_code']);
                unset($arr['组织架构代码']);
            }elseif ($key=='organization_structure_name'&& $v=='组织架构名称'){
                unset($arr['organization_structure_name']);
                unset($arr['组织架构名称']);
            }elseif ($key=='investor_type'&& $v=='投资者类型'){
                unset($arr['investor_type']);
                unset($arr['投资者类型']);
            }elseif ($key=='money_pledge_amount'&& $v=='货币质入金额'){
                unset($arr['money_pledge_amount']);
                unset($arr['货币质入金额']);
            }elseif ($key=='money_pledge_amount_out'&& $v=='货币质出金额'){
                unset($arr['money_pledge_amount_out']);
                unset($arr['货币质出金额']);
            }elseif ($key=='pledge_amount'&& $v=='质押金额'){
                unset($arr['pledge_amount']);
                unset($arr['质押金额']);
            }elseif ($key=='change_amount_of_pledge'&& $v=='质押变动金额'){
                unset($arr['change_amount_of_pledge']);
                unset($arr['质押变动金额']);
            }elseif ($key=='hand_in_fee'&& $v=='上交手续费'){
                unset($arr['hand_in_fee']);
                unset($arr['上交手续费']);
            }elseif ($key=='retention_fee'&& $v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }elseif ($key=='occupation_of_money_pledge_deposit'&& $v=='货币质押保证金占用'){
                unset($arr['occupation_of_money_pledge_deposit']);
                unset($arr['货币质押保证金占用']);
            }elseif ($key=='exchange_margin'&& $v=='交易所保证金'){
                unset($arr['exchange_margin']);
                unset($arr['交易所保证金']);
            }elseif ($key=='delivery_margin'&& $v=='交割保证金'){
                unset($arr['delivery_margin']);
                unset($arr['交割保证金']);
            }elseif ($key=='net_profit'&& $v=='净利润'){
                unset($arr['net_profit']);
                unset($arr['净利润']);
            }elseif ($key=='exchange_risk'&& $v=='交易所风险度'){
                unset($arr['exchange_risk']);
                unset($arr['交易所风险度']);
            }elseif ($key=='exercise_fee'&& $v=='行权手续费'){
                unset($arr['exercise_fee']);
                unset($arr['行权手续费']);
            }elseif ($key=='commission_for_exercise_of_stock_exchange'&& $v=='交易所行权手续费'){
                unset($arr['commission_for_exercise_of_stock_exchange']);
                unset($arr['交易所行权手续费']);
            }elseif ($key=='market_value_equity'&& $v=='市值权益'){
                unset($arr['market_value_equity']);
                unset($arr['市值权益']);
            }elseif ($key=='market_value_of_options_long_positions'&& $v=='期权多头持仓市值'){
                unset($arr['market_value_of_options_long_positions']);
                unset($arr['期权多头持仓市值']);
            }elseif ($key=='market_value_of_short_positions_in_options'&& $v=='期权空头持仓市值'){
                unset($arr['market_value_of_short_positions_in_options']);
                unset($arr['期权空头持仓市值']);
            }elseif ($key=='income_from_option_premium'&& $v=='期权权利金收入'){
                unset($arr['income_from_option_premium']);
                unset($arr['期权权利金收入']);
            }elseif ($key=='option_premium_expenditure'&& $v=='期权权利金支出'){
                unset($arr['option_premium_expenditure']);
                unset($arr['期权权利金支出']);
            }elseif ($key=='break_even_option'&& $v=='期权执行盈亏'){
                unset($arr['break_even_option']);
                unset($arr['期权执行盈亏']);
            }elseif ($key=='change_amount_of_currency_pledge'&& $v=='货币质押变化金额'){
                unset($arr['change_amount_of_currency_pledge']);
                unset($arr['货币质押变化金额']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="huaxin_transaction"){
        foreach ($arr as $key => $v){
            if ($key=='types_of_investors'&& $v=='投资者类型'){
                unset($arr['types_of_investors']);
                unset($arr['投资者类型']);
            }elseif ($key=='currency_code'&&$v=='币种代码'){
                unset($arr['currency_code']);
                unset($arr['币种代码']);
            }elseif ($key=='handing_in_the_service_charge'&& $v=='上交手续费'){
                unset($arr['handing_in_the_service_charge']);
                unset($arr['上交手续费']);
            }elseif ($key=='retention_fee'&& $v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }elseif ($key=='trading_volume_of_the_platform'&& $v=='本平台成交量'){
                unset($arr['trading_volume_of_the_platform']);
                unset($arr['本平台成交量']);
            }elseif ($key=='average_amount_of_fees_charged_on_this_platform'&& $v=='本平台收费平今量'){
                unset($arr['average_amount_of_fees_charged_on_this_platform']);
                unset($arr['本平台收费平今量']);
            }elseif ($key=='this_platform_is_free_of_charge'&& $v=='本平台免费平今量'){
                unset($arr['this_platform_is_free_of_charge']);
                unset($arr['本平台免费平今量']);
            }elseif ($key=='net_profit'&& $v=='净利润'){
                unset($arr['net_profit']);
                unset($arr['净利润']);
            }elseif ($key=='delivery_commission'&& $v=='交割手续费'){
                unset($arr['delivery_commission']);
                unset($arr['交割手续费']);
            }elseif ($key=='delivery_service_charge'&& $v=='上交交割手续费'){
                unset($arr['delivery_service_charge']);
                unset($arr['上交交割手续费']);
            }elseif ($key=='handling_fee_for_retained_delivery'&& $v=='留存交割手续费'){
                unset($arr['handling_fee_for_retained_delivery']);
                unset($arr['留存交割手续费']);
            }elseif ($key=='delivery_quantity'&& $v=='交割数量'){
                unset($arr['delivery_quantity']);
                unset($arr['交割数量']);
            }elseif ($key=='exchange_margin'&& $v=='交易所保证金'){
                unset($arr['exchange_margin']);
                unset($arr['交易所保证金']);
            }elseif ($key=='market_value_of_long_options'&& $v=='多头期权市值'){
                unset($arr['market_value_of_long_options']);
                unset($arr['多头期权市值']);
            }elseif ($key=='market_value_of_short_options'&& $v=='空头期权市值'){
                unset($arr['market_value_of_short_options']);
                unset($arr['空头期权市值']);
            }elseif ($key=='royalty_income'&& $v=='权利金收入'){
                unset($arr['royalty_income']);
                unset($arr['权利金收入']);
            }elseif ($key=='royalty_payment'&& $v=='权利金支出'){
                unset($arr['royalty_payment']);
                unset($arr['权利金支出']);
            }elseif ($key=='commission_for_exercise_of_stock_exchange'&& $v=='交易所行权手续费'){
                unset($arr['commission_for_exercise_of_stock_exchange']);
                unset($arr['交易所行权手续费']);
            }elseif ($key=='exercise_fee'&& $v=='行权手续费'){
                unset($arr['exercise_fee']);
                unset($arr['行权手续费']);
            }elseif ($key=='operating_profit_and_loss'&& $v=='执行盈亏'){
                unset($arr['operating_profit_and_loss']);
                unset($arr['执行盈亏']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="huaxin_deposit_and_withdrawal"){
        foreach ($arr as $key => $v){
            if ($key=='bank'&& $v=='银行'){
                unset($arr['bank']);
                unset($arr['银行']);
            }elseif ($key=='currency_code'&&$v=='币种代码'){
                unset($arr['currency_code']);
                unset($arr['币种代码']);
            }elseif ($key=='abstract'&& $v=='摘要'){
                unset($arr['abstract']);
                unset($arr['摘要']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="huaixn_history"){
        foreach ($arr as $key => $v){
            if ($key=='transaction_number'&& $v=='成交编号'){
                unset($arr['transaction_number']);
                unset($arr['成交编号']);
            }elseif ($key=='currency_code'&&$v=='币种代码'){
                unset($arr['currency_code']);
                unset($arr['币种代码']);
            }elseif ($key=='exchange_trader_code'&& $v=='交易所交易员代码'){
                unset($arr['exchange_trader_code']);
                unset($arr['交易所交易员代码']);
            }elseif ($key=='report_reference'&& $v=='报单引用'){
                unset($arr['report_reference']);
                unset($arr['报单引用']);
            }elseif ($key=='user_designation_codes'&& $v=='用户代码'){
                unset($arr['user_designation_codes']);
                unset($arr['用户代码']);
            }elseif ($key=='service_charge_collection_method'&& $v=='手续费收取方式'){
                unset($arr['service_charge_collection_method']);
                unset($arr['手续费收取方式']);
            }elseif ($key=='service_rate'&& $v=='手续费率'){
                unset($arr['service_rate']);
                unset($arr['手续费率']);
            }elseif ($key=='exchange_commission'&& $v=='交易所手续费'){
                unset($arr['exchange_commission']);
                unset($arr['交易所手续费']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="jinkong_client_funds"){
        foreach ($arr as $key => $v){
            if ($key=='department'&& $v=='部门/属性名称'){
                unset($arr['department']);
                unset($arr['部门/属性名称']);
            }elseif ($key=='types_of_investors'&&$v=='投资者类型'){
                unset($arr['types_of_investors']);
                unset($arr['投资者类型']);
            }elseif ($key=='money_pledge_amount'&& $v=='货币质押金额'){
                unset($arr['money_pledge_amount']);
                unset($arr['货币质押金额']);
            }elseif ($key=='change_of_currency_pledge'&& $v=='货币质押变动'){
                unset($arr['change_of_currency_pledge']);
                unset($arr['货币质押变动']);
            }elseif ($key=='change_amount_of_pledge'&& $v=='质押变动金额'){
                unset($arr['change_amount_of_pledge']);
                unset($arr['质押变动金额']);
            }elseif ($key=='declaration_fee'&& $v=='申报费'){
                unset($arr['declaration_fee']);
                unset($arr['申报费']);
            }elseif ($key=='hand_in_fee'&& $v=='上交手续费'){
                unset($arr['hand_in_fee']);
                unset($arr['上交手续费']);
            }elseif ($key=='retention_fee'&& $v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }elseif ($key=='investor_protection_fund'&& $v=='投资者保障基金'){
                unset($arr['investor_protection_fund']);
                unset($arr['投资者保障基金']);
            }elseif ($key=='handling_charge_turnover_rate'&& $v=='手续费周转率%'){
                unset($arr['handling_charge_turnover_rate']);
                unset($arr['手续费周转率%']);
            }elseif ($key=='software_cost'&& $v=='软件费用'){
                unset($arr['software_cost']);
                unset($arr['软件费用']);
            }elseif ($key=='returning_a_servant'&& $v=='返佣'){
                unset($arr['returning_a_servant']);
                unset($arr['返佣']);
            }elseif ($key=='exchange_margin'&& $v=='交易所保证金'){
                unset($arr['exchange_margin']);
                unset($arr['交易所保证金']);
            }elseif ($key=='Risk_2'&& $v=='风险度2%'){
                unset($arr['Risk_2']);
                unset($arr['风险度2%']);
            }elseif ($key=='occupation_of_money_pledge_deposit'&& $v=='货币质押保证金占用'){
                unset($arr['occupation_of_money_pledge_deposit']);
                unset($arr['货币质押保证金占用']);
            }elseif ($key=='pledge_amount'&& $v=='质押金额'){
                unset($arr['pledge_amount']);
                unset($arr['质押金额']);
            }elseif ($key=='customer_equity_peak'&& $v=='客户权益峰值'){
                unset($arr['customer_equity_peak']);
                unset($arr['客户权益峰值']);
            }elseif ($key=='customer_margin_peak'&& $v=='客户保证金峰值'){
                unset($arr['customer_margin_peak']);
                unset($arr['客户保证金峰值']);
            }elseif ($key=='peak_amount_of_funds_available_to_customers'&& $v=='客户可用资金峰值'){
                unset($arr['peak_amount_of_funds_available_to_customers']);
                unset($arr['客户可用资金峰值']);
            }elseif ($key=='daily_average_exchange_available'&& $v=='日均交易所可用'){
                unset($arr['daily_average_exchange_available']);
                unset($arr['日均交易所可用']);
            }elseif ($key=='daily_daily_average_company_available'&& $v=='日均公司可用'){
                unset($arr['daily_daily_average_company_available']);
                unset($arr['日均公司可用']);
            }elseif ($key=='daily_average_at_home'&& $v=='在户日均'){
                unset($arr['daily_average_at_home']);
                unset($arr['在户日均']);
            }elseif ($key=='average_daily_equity'&& $v=='日均权益'){
                unset($arr['average_daily_equity']);
                unset($arr['日均权益']);
            }elseif ($key=='exercise_fee'&& $v=='行权手续费'){
                unset($arr['exercise_fee']);
                unset($arr['行权手续费']);
            }elseif ($key=='commission_for_exercise_of_stock_exchange'&& $v=='交易所行权手续费'){
                unset($arr['commission_for_exercise_of_stock_exchange']);
                unset($arr['交易所行权手续费']);
            }elseif ($key=='performance_fee'&& $v=='履约手续费'){
                unset($arr['performance_fee']);
                unset($arr['履约手续费']);
            }elseif ($key=='service_charge_for_performance_of_the_exchange'&& $v=='交易所履约手续费'){
                unset($arr['service_charge_for_performance_of_the_exchange']);
                unset($arr['交易所履约手续费']);
            }elseif ($key=='market_value_equity'&& $v=='市值权益'){
                unset($arr['market_value_equity']);
                unset($arr['市值权益']);
            }elseif ($key=='market_value_of_options_long_positions'&& $v=='期权多头持仓市值'){
                unset($arr['market_value_of_options_long_positions']);
                unset($arr['期权多头持仓市值']);
            }elseif ($key=='market_value_of_short_positions_in_options'&& $v=='期权空头持仓市值'){
                unset($arr['market_value_of_short_positions_in_options']);
                unset($arr['期权空头持仓市值']);
            }elseif ($key=='income_from_option_premium'&& $v=='期权权利金收入'){
                unset($arr['income_from_option_premium']);
                unset($arr['期权权利金收入']);
            }elseif ($key=='option_premium_expenditure'&& $v=='期权权利金支出'){
                unset($arr['option_premium_expenditure']);
                unset($arr['期权权利金支出']);
            }elseif ($key=='profit_and_loss_of_option_exercise'&& $v=='期权执行盈亏'){
                unset($arr['profit_and_loss_of_option_exercise']);
                unset($arr['期权执行盈亏']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="jinkong_transaction"){
        foreach ($arr as $key => $v){
            if ($key=='types_of_investors'&& $v=='投资者类型'){
                unset($arr['types_of_investors']);
                unset($arr['投资者类型']);
            }elseif ($key=='currency_code'&&$v=='币种代码'){
                unset($arr['currency_code']);
                unset($arr['币种代码']);
            }elseif ($key=='insure'&& $v=='投保'){
                unset($arr['insure']);
                unset($arr['投保']);
            }elseif ($key=='handing_in_the_service_charge'&& $v=='上交手续费'){
                unset($arr['handing_in_the_service_charge']);
                unset($arr['上交手续费']);
            }elseif ($key=='retention_fee'&& $v=='留存手续费'){
                unset($arr['retention_fee']);
                unset($arr['留存手续费']);
            }elseif ($key=='royalty_income'&& $v=='权利金收入'){
                unset($arr['royalty_income']);
                unset($arr['权利金收入']);
            }elseif ($key=='royalty_payment'&& $v=='权利金支出'){
                unset($arr['royalty_payment']);
                unset($arr['权利金支出']);
            }
        }
    }
    if ($isadmin=='0'||$isadmin== '2' && $tableName=="jinkong_deposit_and_withdrawal"){
        foreach ($arr as $key => $v){
            if ($key=='bank'&& $v=='银行'){
                unset($arr['bank']);
                unset($arr['银行']);
            }elseif ($key=='currency_code'&&$v=='币种代码'){
                unset($arr['currency_code']);
                unset($arr['币种代码']);
            }elseif ($key=='abstract'&& $v=='摘要'){
                unset($arr['abstract']);
                unset($arr['摘要']);
            }
        }
    }
    return $arr;
  }
    /**
     * @description: 获取一个日期范围内的日期
     * @param {interval:日期范围,type：取值类型，-：获取之前日期；+：获取之后的日期}
     * @return:
     */
    protected function getDateInterval($interval,$type)
    {
        $dateArr = [];
        for ($i = $interval - 1; $i >= 0; $i--) {
            array_push($dateArr, date('Ymd', strtotime("{$type}{$i} day")));
        }
        if($type=='+')$dateArr=array_reverse($dateArr);
        return $dateArr;
    }

    /**
     * 查找表数据
     */
    public function getTableData($tableName, $start,$group,$isadmin,$startDate,$endtDate,$account,$customerame,$searchGroup,$sortField,$sortType)
    {
        //排序的条件判断
        if($sortField){
            if ($sortType){
                $where3= "$sortField DESC";
            }else{
                $where3= "$sortField";
            }
        }else{
            $where3=true;
        }
        //经理和主任只能看到历史30天的数据
        $restrictedDate=$this->getDateInterval(30,'-');
        if (($isadmin=='0'||$isadmin=='2')&&in_array($startDate,$restrictedDate)&&in_array($endtDate,$restrictedDate)){
            $startDate=$startDate;
            $endtDate=$endtDate;
            $account=$account;
            $customerame=$customerame;
        }elseif($isadmin=='0'||$isadmin=='2'){
            $startDate='';
            $endtDate='';
            $account='';
            $customerame='';
        }

        //经理和主任恒银权限
        if($isadmin== '2' && $tableName=="hengyin_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['handing_in_service_charge','retention_fee','net_profit_and_loss','service_charge_handed_in_at_present'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(customer_service_charge),sum(retention_fee),sum(transaction_amount)
                 ,sum(number_of_transactions),sum(today_hand_count),sum(current_service_charge)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0' && $tableName=="hengyin_transaction"){
            if (($startDate && $endtDate) || $account || $customerame ||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }

                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['handing_in_service_charge','retention_fee','net_profit_and_loss','service_charge_handed_in_at_present'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(customer_service_charge),sum(retention_fee),sum(transaction_amount)
                 ,sum(number_of_transactions),sum(today_hand_count),sum(current_service_charge)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2' && $tableName=="hengyin_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate and $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }

                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`equity_at_the_beginning_of_the_period` != '0.00' OR  `ending_equity` != '0.00' OR `deposit` != '0.00'";
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['hand_in_fee','exchange_margin'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->hidden(['hand_in_fee','exchange_margin'])
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(total_profit_and_loss),sum(handling_fee)
                 ,sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(available_at_the_end_of_the_term)
                 ,sum(bond),sum(ending_equity)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->hidden(['hand_in_fee','exchange_margin'])
                    ->count();
            }
        }elseif ($isadmin=='0' && $tableName=="hengyin_client_funds"){
            if (($startDate && $endtDate) || $account || $customerame ||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }

                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`equity_at_the_beginning_of_the_period` != '0.00' OR  `ending_equity` != '0.00' OR `deposit` != '0.00'";
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['hand_in_fee','exchange_margin'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->hidden(['hand_in_fee','exchange_margin'])
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(total_profit_and_loss),sum(handling_fee)
                 ,sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(available_at_the_end_of_the_term)
                 ,sum(bond),sum(ending_equity)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->hidden(['hand_in_fee','exchange_margin'])
                    ->count();
            }
        }elseif ($isadmin=='2' && $tableName=="sanli_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`equity_at_the_beginning_of_the_period` != '0000.00' OR  `ending_equity` != '0000.00' OR `deposit` != '0000.00'";
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['net_profit_and_loss','hand_in_fee','retention_fee'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(deposit),sum(withdrawal)
                 ,sum(deposit_and_withdrawal),sum(total_profit_and_loss),sum(net_profit_and_loss),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),
                 sum(ending_equity),sum(bond),sum(available_at_the_end_of_the_term),sum(average_daily_equity)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->count();
            }
        }elseif ($isadmin=='0' && $tableName=="sanli_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`equity_at_the_beginning_of_the_period` != '0000.00' OR  `ending_equity` != '0000.00' OR `deposit` != '0000.00'";
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['net_profit_and_loss','hand_in_fee','retention_fee'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(deposit),sum(withdrawal)
                 ,sum(deposit_and_withdrawal),sum(total_profit_and_loss),sum(net_profit_and_loss),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),
                 sum(ending_equity),sum(bond),sum(available_at_the_end_of_the_term),sum(average_daily_equity)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->count();
            }
        }elseif ($isadmin=='2' && $tableName=="sanli_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['sales_department','customer_classification','futures_classification','futures_grouping','transaction_time','handing_in_service_charge','retention_fee',
                        'option_exercise_opening_amount','number_of_options_executed','number_of_abandoned_options','number_of_open_positions_under_option_execution',
                        'service_charge_handed_in_at_present','transaction_code','floating_profit_and_loss_of_option_closing','income_royalty','payment_royalty','royalty','organization_logo'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_service_charge),sum(retention_fee)
                 ,sum(transaction_price),sum(transaction_amount),sum(option_exercise_opening_amount),sum(hand_count),sum(number_of_options_executed),sum(number_of_abandoned_options),
                 sum(number_of_open_positions_under_option_execution),sum(today_hand_count),sum(current_service_charge),sum(service_charge_handed_in_at_present),
                 sum(floating_profit_and_loss_of_option_closing),sum(income_royalty),sum(payment_royalty),sum(royalty)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0' && $tableName=="sanli_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['sales_department','customer_classification','futures_classification','futures_grouping','transaction_time','handing_in_service_charge','retention_fee',
                        'option_exercise_opening_amount','number_of_options_executed','number_of_abandoned_options','number_of_open_positions_under_option_execution',
                        'service_charge_handed_in_at_present','transaction_code','floating_profit_and_loss_of_option_closing','income_royalty','payment_royalty','royalty','organization_logo'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_service_charge),sum(retention_fee)
                 ,sum(transaction_price),sum(transaction_amount),sum(option_exercise_opening_amount),sum(hand_count),sum(number_of_options_executed),sum(number_of_abandoned_options),
                 sum(number_of_open_positions_under_option_execution),sum(today_hand_count),sum(current_service_charge),sum(service_charge_handed_in_at_present),
                 sum(floating_profit_and_loss_of_option_closing),sum(income_royalty),sum(payment_royalty),sum(royalty)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="ruida_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['retention_fee','hand_in_fee','net_handling_charge','returning_a_servant','exchange_margin','risk_2','delivery_margin',
                        'exchange_settlement_margin','customer_class','customer_class_name','money_pledge_amount',
                        'foreign_exchange_in','foreign_exchange','pledge_amount','execution_fee','hand_in_the_execution_fee','royalty_income_and_expenditure','operating_profit_and_loss',
                        'market_value_of_options','customer_market_value_equity','delivery_commission','delivery_service_charge','total_freeze','value_added_tax',
                        'funds_available_to_exchange_level_clients','frozen_amount_of_currency_pledge','risk_freezing_funds_the_next_day'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal)
                 ,sum(handling_fee),sum(total_profit_and_loss),sum(ending_equity),sum(available_funds),sum(customer_margin),sum(balance_today),
                 sum(retention_fee),sum(closing_profit_and_loss),sum(floating_profit_and_loss),sum(balance_of_last_day),
                 sum(hand_in_fee),sum(net_handling_charge),sum(returning_a_servant),sum(exchange_margin),sum(delivery_margin),sum(exchange_settlement_margin),
                 sum(margin_call),sum(money_pledge_amount),sum(foreign_exchange_in),sum(foreign_exchange),sum(pledge_amount),sum(execution_fee),sum(hand_in_the_execution_fee),
                 sum(royalty_income_and_expenditure),sum(operating_profit_and_loss),sum(market_value_of_options),sum(customer_market_value_equity),
                 sum(delivery_commission),sum(delivery_service_charge),sum(total_freeze),sum(value_added_tax),sum(funds_available_to_exchange_level_clients),
                 sum(frozen_amount_of_currency_pledge),sum(risk_freezing_funds_the_next_day)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="ruida_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['retention_fee','hand_in_fee','net_handling_charge','returning_a_servant','exchange_margin','risk_2','delivery_margin',
                        'exchange_settlement_margin','customer_class','customer_class_name','money_pledge_amount',
                        'foreign_exchange_in','foreign_exchange','pledge_amount','execution_fee','hand_in_the_execution_fee','royalty_income_and_expenditure','operating_profit_and_loss',
                        'market_value_of_options','customer_market_value_equity','delivery_commission','delivery_service_charge','total_freeze','value_added_tax',
                        'funds_available_to_exchange_level_clients','frozen_amount_of_currency_pledge','risk_freezing_funds_the_next_day'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal)
                 ,sum(handling_fee),sum(total_profit_and_loss),sum(ending_equity),sum(available_funds),sum(customer_margin),sum(balance_today),
                 sum(retention_fee),sum(closing_profit_and_loss),sum(floating_profit_and_loss),sum(balance_of_last_day),
                 sum(hand_in_fee),sum(net_handling_charge),sum(returning_a_servant),sum(exchange_margin),sum(delivery_margin),sum(exchange_settlement_margin),
                 sum(margin_call),sum(money_pledge_amount),sum(foreign_exchange_in),sum(foreign_exchange),sum(pledge_amount),sum(execution_fee),sum(hand_in_the_execution_fee),
                 sum(royalty_income_and_expenditure),sum(operating_profit_and_loss),sum(market_value_of_options),sum(customer_market_value_equity),
                 sum(delivery_commission),sum(delivery_service_charge),sum(total_freeze),sum(value_added_tax),sum(funds_available_to_exchange_level_clients),
                 sum(frozen_amount_of_currency_pledge),sum(risk_freezing_funds_the_next_day)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="ruida_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['transaction_no','handing_in_the_service_charge','transaction_code','trader_number','system_number','elegation_type','specified_price',
                        'currency','transaction_number_of_the_exchange','quantity_per_hand','mark_of_current_cost_calculation',
                        'surcharge_1','surcharge_2','other_charges','strong_flat_sign','product_identification','single_channel'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(transaction_price),sum(turnover),sum(service_charge)
                 ,sum(handing_in_the_service_charge),sum(specified_price),sum(quantity_per_hand),sum(mark_of_current_cost_calculation),sum(surcharge_1),sum(surcharge_2),
                 sum(other_charges)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="ruida_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['transaction_no','handing_in_the_service_charge','transaction_code','trader_number','system_number','elegation_type','specified_price',
                        'currency','transaction_number_of_the_exchange','quantity_per_hand','mark_of_current_cost_calculation',
                        'surcharge_1','surcharge_2','other_charges','strong_flat_sign','product_identification','single_channel'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(transaction_price),sum(turnover),sum(service_charge)
                 ,sum(handing_in_the_service_charge),sum(specified_price),sum(quantity_per_hand),sum(mark_of_current_cost_calculation),sum(surcharge_1),sum(surcharge_2),
                 sum(other_charges)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="ruida_deposit_and_withdrawal"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['serial_number','type_of_deposit_and_withdrawal','fund_type','bank','abstract','customer_class'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="ruida_deposit_and_withdrawal"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['serial_number','type_of_deposit_and_withdrawal','fund_type','bank','abstract','customer_class'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where('category','瑞达')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="huaxin_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`beginning_equity` != '0.00' OR  `ending_equity` != '0.00' OR `deposit` != '0.00'";
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['settlement_no','currency_code','organization_code','organization_structure_name','investor_type','money_pledge_amount','money_pledge_amount_out',
                        'pledge_amount','change_amount_of_pledge','hand_in_fee','retention_fee','occupation_of_money_pledge_deposit',
                        'exchange_margin','delivery_margin','net_profit','exchange_risk','exercise_fee','commission_for_exercise_of_stock_exchange','market_value_equity','market_value_of_options_long_positions',
                        'market_value_of_short_positions_in_options','income_from_option_premium','option_premium_expenditure','break_even_option','change_amount_of_currency_pledge'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->field('sum(beginning_equity),sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(money_pledge_amount),sum(money_pledge_amount_out),sum(pledge_amount),
                    sum(change_amount_of_pledge),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),sum(position_profit_and_loss),sum(closing_profit_and_loss),sum(total_profit_and_loss),
                    sum(investor_margin),sum(occupation_of_money_pledge_deposit),sum(exchange_margin),sum(delivery_margin),sum(margin_call),sum(available_funds),sum(investors_rights_and_interests),
                    sum(net_profit),sum(ending_equity),sum(exercise_fee),sum(commission_for_exercise_of_stock_exchange),sum(market_value_equity),sum(market_value_of_options_long_positions),
                    sum(market_value_of_short_positions_in_options),sum(income_from_option_premium),sum(option_premium_expenditure),sum(break_even_option),sum(change_amount_of_currency_pledge)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="huaxin_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`beginning_equity` != '0.00' OR  `ending_equity` != '0.00' OR `deposit` != '0.00'";
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['settlement_no','currency_code','organization_code','organization_structure_name','investor_type','money_pledge_amount','money_pledge_amount_out',
                        'pledge_amount','change_amount_of_pledge','hand_in_fee','retention_fee','occupation_of_money_pledge_deposit',
                        'exchange_margin','delivery_margin','net_profit','exchange_risk','exercise_fee','commission_for_exercise_of_stock_exchange','market_value_equity','market_value_of_options_long_positions',
                        'market_value_of_short_positions_in_options','income_from_option_premium','option_premium_expenditure','break_even_option','change_amount_of_currency_pledge'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->field('sum(beginning_equity),sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(money_pledge_amount),sum(money_pledge_amount_out),sum(pledge_amount),
                    sum(change_amount_of_pledge),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),sum(position_profit_and_loss),sum(closing_profit_and_loss),sum(total_profit_and_loss),
                    sum(investor_margin),sum(occupation_of_money_pledge_deposit),sum(exchange_margin),sum(delivery_margin),sum(margin_call),sum(available_funds),sum(investors_rights_and_interests),
                    sum(net_profit),sum(ending_equity),sum(exercise_fee),sum(commission_for_exercise_of_stock_exchange),sum(market_value_equity),sum(market_value_of_options_long_positions),
                    sum(market_value_of_short_positions_in_options),sum(income_from_option_premium),sum(option_premium_expenditure),sum(break_even_option),sum(change_amount_of_currency_pledge)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="huaxin_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['types_of_investors','currency_code','handing_in_the_service_charge','retention_fee','trading_volume_of_the_platform','average_amount_of_fees_charged_on_this_platform',
                        'this_platform_is_free_of_charge','net_profit','delivery_commission','delivery_service_charge','handling_fee_for_retained_delivery','delivery_quantity',
                        'exchange_margin','market_value_of_long_options','market_value_of_short_options','royalty_income','royalty_payment','commission_for_exercise_of_stock_exchange','exercise_fee',
                        'operating_profit_and_loss'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_the_service_charge),sum(retention_fee),sum(profit_and_loss),sum(total_volume),sum(trading_volume_of_the_platform),sum(average_volume),
                    sum(average_amount_of_fees_charged_on_this_platform),sum(this_platform_is_free_of_charge),sum(turnover),sum(current_turnover),sum(closing_profit_and_loss),sum(position_profit_and_loss),
                    sum(net_profit),sum(delivery_commission),sum(delivery_service_charge),sum(handling_fee_for_retained_delivery),sum(delivery_quantity),sum(buy_holding),sum(sales_volume),sum(investor_margin),
                    sum(exchange_margin),sum(market_value_of_long_options),sum(market_value_of_short_options),sum(royalty_income),sum(royalty_payment),sum(commission_for_exercise_of_stock_exchange),
                    sum(exercise_fee),sum(operating_profit_and_loss)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="huaxin_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['types_of_investors','currency_code','handing_in_the_service_charge','retention_fee','trading_volume_of_the_platform','average_amount_of_fees_charged_on_this_platform',
                        'this_platform_is_free_of_charge','net_profit','delivery_commission','delivery_service_charge','handling_fee_for_retained_delivery','delivery_quantity',
                        'exchange_margin','market_value_of_long_options','market_value_of_short_options','royalty_income','royalty_payment','commission_for_exercise_of_stock_exchange','exercise_fee',
                        'operating_profit_and_loss'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_the_service_charge),sum(retention_fee),sum(profit_and_loss),sum(total_volume),sum(trading_volume_of_the_platform),sum(average_volume),
                    sum(average_amount_of_fees_charged_on_this_platform),sum(this_platform_is_free_of_charge),sum(turnover),sum(current_turnover),sum(closing_profit_and_loss),sum(position_profit_and_loss),
                    sum(net_profit),sum(delivery_commission),sum(delivery_service_charge),sum(handling_fee_for_retained_delivery),sum(delivery_quantity),sum(buy_holding),sum(sales_volume),sum(investor_margin),
                    sum(exchange_margin),sum(market_value_of_long_options),sum(market_value_of_short_options),sum(royalty_income),sum(royalty_payment),sum(commission_for_exercise_of_stock_exchange),
                    sum(exercise_fee),sum(operating_profit_and_loss)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="huaxin_deposit_and_withdrawal"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['bank','currency_code','abstract'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out),sum(number_of_gold_entries),sum(gold_output)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="huaxin_deposit_and_withdrawal"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['bank','currency_code','abstract'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out),sum(number_of_gold_entries),sum(gold_output)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="huaixn_history"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['transaction_number','currency_code','exchange_trader_code','report_reference','user_designation_codes','service_charge_collection_method','service_rate','exchange_commission'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(number),sum(price),sum(exchange_commission)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="huaixn_history"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['transaction_number','currency_code','exchange_trader_code','report_reference','user_designation_codes','service_charge_collection_method','service_rate','exchange_commission'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(number),sum(price),sum(exchange_commission)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="jinkong_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`equity_at_the_beginning_of_the_period` != '0.00' OR  `ending_equity` != '0.00' OR `deposit` != '0.00'";
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['department','types_of_investors','money_pledge_amount','change_of_currency_pledge','change_amount_of_pledge','declaration_fee','hand_in_fee',
                        'retention_fee','investor_protection_fund','handling_charge_turnover_rate','software_cost','returning_a_servant',
                        'exchange_margin','Risk_2','occupation_of_money_pledge_deposit','pledge_amount','customer_equity_peak','customer_margin_peak','peak_amount_of_funds_available_to_customers',
                        'daily_average_exchange_available','daily_daily_average_company_available','daily_average_at_home','average_daily_equity','exercise_fee','commission_for_exercise_of_stock_exchange',
                        'performance_fee','service_charge_for_performance_of_the_exchange','market_value_equity','market_value_of_options_long_positions','market_value_of_short_positions_in_options',
                        'income_from_option_premium','option_premium_expenditure','profit_and_loss_of_option_exercise'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(money_pledge_amount),sum(change_of_currency_pledge),sum(change_amount_of_pledge),
                    sum(declaration_fee),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),sum(investor_protection_fund),sum(handling_charge_turnover_rate),sum(software_cost),
                    sum(returning_a_servant),sum(net_retention_fee),sum(number_of_transactions),sum(transaction_amount),sum(today_hand_count),sum(today_current_turnover),sum(total_profit_and_loss),
                    sum(position_profit_and_loss),sum(closing_profit_and_loss),sum(ending_equity),sum(investor_margin),sum(exchange_margin),sum(Risk_1),sum(Risk_2),sum(available_funds),
                    sum(occupation_of_money_pledge_deposit),sum(pledge_amount),sum(customer_equity_peak),sum(customer_margin_peak),sum(peak_amount_of_funds_available_to_customers),
                    sum(daily_average_exchange_available),sum(daily_daily_average_company_available),sum(daily_average_at_home),sum(average_daily_equity),sum(exercise_fee),sum(commission_for_exercise_of_stock_exchange),
                    sum(performance_fee),sum(service_charge_for_performance_of_the_exchange),sum(market_value_equity),sum(market_value_of_options_long_positions),sum(market_value_of_short_positions_in_options),
                    sum(income_from_option_premium),sum(option_premium_expenditure),sum(profit_and_loss_of_option_exercise)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="jinkong_client_funds"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $where2="`equity_at_the_beginning_of_the_period` != '0.00' OR  `ending_equity` != '0.00' OR `deposit` != '0.00'";
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->order($where3)
                    ->hidden(['department','types_of_investors','money_pledge_amount','change_of_currency_pledge','change_amount_of_pledge','declaration_fee','hand_in_fee',
                        'retention_fee','investor_protection_fund','handling_charge_turnover_rate','software_cost','returning_a_servant',
                        'exchange_margin','Risk_2','occupation_of_money_pledge_deposit','pledge_amount','customer_equity_peak','customer_margin_peak','peak_amount_of_funds_available_to_customers',
                        'daily_average_exchange_available','daily_daily_average_company_available','daily_average_at_home','average_daily_equity','exercise_fee','commission_for_exercise_of_stock_exchange',
                        'performance_fee','service_charge_for_performance_of_the_exchange','market_value_equity','market_value_of_options_long_positions','market_value_of_short_positions_in_options',
                        'income_from_option_premium','option_premium_expenditure','profit_and_loss_of_option_exercise'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(money_pledge_amount),sum(change_of_currency_pledge),sum(change_amount_of_pledge),
                    sum(declaration_fee),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),sum(investor_protection_fund),sum(handling_charge_turnover_rate),sum(software_cost),
                    sum(returning_a_servant),sum(net_retention_fee),sum(number_of_transactions),sum(transaction_amount),sum(today_hand_count),sum(today_current_turnover),sum(total_profit_and_loss),
                    sum(position_profit_and_loss),sum(closing_profit_and_loss),sum(ending_equity),sum(investor_margin),sum(exchange_margin),sum(Risk_1),sum(Risk_2),sum(available_funds),
                    sum(occupation_of_money_pledge_deposit),sum(pledge_amount),sum(customer_equity_peak),sum(customer_margin_peak),sum(peak_amount_of_funds_available_to_customers),
                    sum(daily_average_exchange_available),sum(daily_daily_average_company_available),sum(daily_average_at_home),sum(average_daily_equity),sum(exercise_fee),sum(commission_for_exercise_of_stock_exchange),
                    sum(performance_fee),sum(service_charge_for_performance_of_the_exchange),sum(market_value_equity),sum(market_value_of_options_long_positions),sum(market_value_of_short_positions_in_options),
                    sum(income_from_option_premium),sum(option_premium_expenditure),sum(profit_and_loss_of_option_exercise)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->where($where2)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="jinkong_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['types_of_investors','currency_code','insure','handing_in_the_service_charge','retention_fee','royalty_income','royalty_payment'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_the_service_charge),sum(retention_fee),sum(profit_and_loss),sum(total_volume),sum(trading_volume_of_the_platform),
                    sum(average_volume),sum(average_amount_of_fees_charged_on_this_platform),sum(this_platform_is_free_of_charge),sum(turnover),sum(current_turnover),sum(closing_profit_and_loss),
                    sum(position_profit_and_loss),sum(net_profit),sum(delivery_commission),sum(delivery_service_charge),sum(handling_fee_for_retained_delivery),sum(delivery_quantity),
                    sum(buy_holding),sum(sales_volume),sum(investor_margin),sum(exchange_margin),sum(market_value_of_long_options),sum(market_value_of_short_options),sum(royalty_income),
                    sum(royalty_payment),sum(commission_for_exercise_of_stock_exchange),sum(exercise_fee),sum(operating_profit_and_loss)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="jinkong_transaction"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['types_of_investors','currency_code','insure','handing_in_the_service_charge','retention_fee','royalty_income','royalty_payment'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_the_service_charge),sum(retention_fee),sum(profit_and_loss),sum(total_volume),sum(trading_volume_of_the_platform),
                    sum(average_volume),sum(average_amount_of_fees_charged_on_this_platform),sum(this_platform_is_free_of_charge),sum(turnover),sum(current_turnover),sum(closing_profit_and_loss),
                    sum(position_profit_and_loss),sum(net_profit),sum(delivery_commission),sum(delivery_service_charge),sum(handling_fee_for_retained_delivery),sum(delivery_quantity),
                    sum(buy_holding),sum(sales_volume),sum(investor_margin),sum(exchange_margin),sum(market_value_of_long_options),sum(market_value_of_short_options),sum(royalty_income),
                    sum(royalty_payment),sum(commission_for_exercise_of_stock_exchange),sum(exercise_fee),sum(operating_profit_and_loss)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='2'&& $tableName=="jinkong_deposit_and_withdrawal"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['bank','currency_code','abstract'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out),sum(number_of_gold_entries),sum(gold_output)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups','like',$group .'%')
                    ->where($where)
                    ->where($where1)
                    ->count();
            }
        }elseif ($isadmin=='0'&& $tableName=="jinkong_deposit_and_withdrawal"){
            if(($startDate && $endtDate) || $account || $customerame||$searchGroup){
                if ($startDate && $endtDate){
                    $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
                }else{
                    $where=true;
                }
                if ($account && $customerame&& $searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account&&$searchGroup){
                    $where1=array(
                        'customer_number'=>$account,
                        'groups'=>$searchGroup
                    );
                }elseif ($customerame&&$searchGroup){
                    $where1=array(
                        'customer_name'=>$customerame,
                        'groups'=>$searchGroup
                    );
                }elseif ($account){
                    $where1=array(
                        'customer_number'=>$account
                    );
                }elseif ($customerame){
                    $where1=array(
                        'customer_name'=>$customerame
                    );
                }elseif ($searchGroup){
                    $where1=array(
                        'groups'=>$searchGroup
                    );
                }else{
                    $where1=true;
                }
                $data = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->order($where3)
                    ->hidden(['bank','currency_code','abstract'])
                    ->limit($start, 30)
                    ->select();
                $collect = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out),sum(number_of_gold_entries),sum(gold_output)')
                    ->find();
                $count = DB::name($tableName)
                    ->where('groups',$group)
                    ->where($where)
                    ->where($where1)
                    ->count();
            }

        }elseif ($isadmin=='1'){
            //超级管理员
            if ($startDate && $endtDate){
                $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate";
            }else{
                $where=true;
            }

            if ($account && $customerame&& $searchGroup){
                $where1=array(
                    'customer_number'=>$account,
                    'customer_name'=>$customerame,
                    'groups'=>$searchGroup
                );
            }elseif ($account&&$searchGroup){
                $where1=array(
                    'customer_number'=>$account,
                    'groups'=>$searchGroup
                );
            }elseif ($customerame&&$searchGroup){
                $where1=array(
                    'customer_name'=>$customerame,
                    'groups'=>$searchGroup
                );
            }elseif ($account){
                $where1=array(
                    'customer_number'=>$account
                );
            }elseif ($customerame){
                $where1=array(
                    'customer_name'=>$customerame
                );
            }elseif ($searchGroup){
                $where1=array(
                    'groups'=>$searchGroup
                );
            }else{
                $where1=true;
            }
            //查询数据
            $data = DB::name($tableName)
                ->where($where)
                ->where($where1)
                ->order($where3)
                ->limit($start, 30)
                ->select();

            if($tableName=='hengyin_client_funds'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(total_profit_and_loss),sum(handling_fee)
             ,sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(available_at_the_end_of_the_term)
             ,sum(bond),sum(ending_equity)')->find();
            }elseif ($tableName=='hengyin_transaction'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(customer_service_charge),sum(retention_fee),sum(transaction_amount)
             ,sum(number_of_transactions),sum(today_hand_count),sum(current_service_charge)')->find();
            }elseif ($tableName=='sanli_client_funds'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(deposit),sum(withdrawal)
                 ,sum(deposit_and_withdrawal),sum(total_profit_and_loss),sum(net_profit_and_loss),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),
                 sum(ending_equity),sum(bond),sum(available_at_the_end_of_the_term),sum(average_daily_equity)')
                    ->find();
            }elseif ($tableName=='sanli_transaction'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_service_charge),sum(retention_fee)
                 ,sum(transaction_price),sum(transaction_amount),sum(option_exercise_opening_amount),sum(hand_count),sum(number_of_options_executed),sum(number_of_abandoned_options),
                 sum(number_of_open_positions_under_option_execution),sum(today_hand_count),sum(current_service_charge),sum(service_charge_handed_in_at_present),
                 sum(floating_profit_and_loss_of_option_closing),sum(income_royalty),sum(payment_royalty),sum(royalty)')
                    ->find();
            }elseif ($tableName=='ruida_client_funds'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal)
                 ,sum(handling_fee),sum(total_profit_and_loss),sum(ending_equity),sum(available_funds),sum(customer_margin),sum(balance_today),
                 sum(retention_fee),sum(closing_profit_and_loss),sum(floating_profit_and_loss),sum(balance_of_last_day),
                 sum(hand_in_fee),sum(net_handling_charge),sum(returning_a_servant),sum(exchange_margin),sum(delivery_margin),sum(exchange_settlement_margin),
                 sum(margin_call),sum(money_pledge_amount),sum(foreign_exchange_in),sum(foreign_exchange),sum(pledge_amount),sum(execution_fee),sum(hand_in_the_execution_fee),
                 sum(royalty_income_and_expenditure),sum(operating_profit_and_loss),sum(market_value_of_options),sum(customer_market_value_equity),
                 sum(delivery_commission),sum(delivery_service_charge),sum(total_freeze),sum(value_added_tax),sum(funds_available_to_exchange_level_clients),
                 sum(frozen_amount_of_currency_pledge),sum(risk_freezing_funds_the_next_day)')
                    ->find();
            }elseif ($tableName=='ruida_transaction'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(transaction_price),sum(turnover),sum(service_charge)
                 ,sum(handing_in_the_service_charge),sum(specified_price),sum(quantity_per_hand),sum(mark_of_current_cost_calculation),sum(surcharge_1),sum(surcharge_2),
                 sum(other_charges)')->find();
            }elseif ($tableName=='ruida_deposit_and_withdrawal'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out)')
                    ->find();
            }elseif ($tableName=='huaxin_client_funds'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(beginning_equity),sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(money_pledge_amount),sum(money_pledge_amount_out),sum(pledge_amount),
                    sum(change_amount_of_pledge),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),sum(position_profit_and_loss),sum(closing_profit_and_loss),sum(total_profit_and_loss),
                    sum(investor_margin),sum(occupation_of_money_pledge_deposit),sum(exchange_margin),sum(delivery_margin),sum(margin_call),sum(available_funds),sum(investors_rights_and_interests),
                    sum(net_profit),sum(ending_equity),sum(exercise_fee),sum(commission_for_exercise_of_stock_exchange),sum(market_value_equity),sum(market_value_of_options_long_positions),
                    sum(market_value_of_short_positions_in_options),sum(income_from_option_premium),sum(option_premium_expenditure),sum(break_even_option),sum(change_amount_of_currency_pledge)')
                    ->find();
            }elseif ($tableName=='huaxin_transaction'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_the_service_charge),sum(retention_fee),sum(profit_and_loss),sum(total_volume),sum(trading_volume_of_the_platform),sum(average_volume),
                    sum(average_amount_of_fees_charged_on_this_platform),sum(this_platform_is_free_of_charge),sum(turnover),sum(current_turnover),sum(closing_profit_and_loss),sum(position_profit_and_loss),
                    sum(net_profit),sum(delivery_commission),sum(delivery_service_charge),sum(handling_fee_for_retained_delivery),sum(delivery_quantity),sum(buy_holding),sum(sales_volume),sum(investor_margin),
                    sum(exchange_margin),sum(market_value_of_long_options),sum(market_value_of_short_options),sum(royalty_income),sum(royalty_payment),sum(commission_for_exercise_of_stock_exchange),
                    sum(exercise_fee),sum(operating_profit_and_loss)')
                    ->find();
            }elseif ($tableName=='huaxin_deposit_and_withdrawal'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out),sum(number_of_gold_entries),sum(gold_output)')
                    ->find();
            }elseif ($tableName=='huaixn_history'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(number),sum(price),sum(exchange_commission)')
                    ->find();
            }elseif ($tableName=='jinkong_client_funds'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(equity_at_the_beginning_of_the_period),sum(deposit),sum(withdrawal),sum(deposit_and_withdrawal),sum(money_pledge_amount),sum(change_of_currency_pledge),sum(change_amount_of_pledge),
                    sum(declaration_fee),sum(handling_fee),sum(hand_in_fee),sum(retention_fee),sum(investor_protection_fund),sum(handling_charge_turnover_rate),sum(software_cost),
                    sum(returning_a_servant),sum(net_retention_fee),sum(number_of_transactions),sum(transaction_amount),sum(today_hand_count),sum(today_current_turnover),sum(total_profit_and_loss),
                    sum(position_profit_and_loss),sum(closing_profit_and_loss),sum(ending_equity),sum(investor_margin),sum(exchange_margin),sum(Risk_1),sum(Risk_2),sum(available_funds),
                    sum(occupation_of_money_pledge_deposit),sum(pledge_amount),sum(customer_equity_peak),sum(customer_margin_peak),sum(peak_amount_of_funds_available_to_customers),
                    sum(daily_average_exchange_available),sum(daily_daily_average_company_available),sum(daily_average_at_home),sum(average_daily_equity),sum(exercise_fee),sum(commission_for_exercise_of_stock_exchange),
                    sum(performance_fee),sum(service_charge_for_performance_of_the_exchange),sum(market_value_equity),sum(market_value_of_options_long_positions),sum(market_value_of_short_positions_in_options),
                    sum(income_from_option_premium),sum(option_premium_expenditure),sum(profit_and_loss_of_option_exercise)')
                    ->find();
            }elseif ($tableName=='jinkong_transaction'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(service_charge),sum(handing_in_the_service_charge),sum(retention_fee),sum(profit_and_loss),sum(total_volume),sum(trading_volume_of_the_platform),
                    sum(average_volume),sum(average_amount_of_fees_charged_on_this_platform),sum(this_platform_is_free_of_charge),sum(turnover),sum(current_turnover),sum(closing_profit_and_loss),
                    sum(position_profit_and_loss),sum(net_profit),sum(delivery_commission),sum(delivery_service_charge),sum(handling_fee_for_retained_delivery),sum(delivery_quantity),
                    sum(buy_holding),sum(sales_volume),sum(investor_margin),sum(exchange_margin),sum(market_value_of_long_options),sum(market_value_of_short_options),sum(royalty_income),
                    sum(royalty_payment),sum(commission_for_exercise_of_stock_exchange),sum(exercise_fee),sum(operating_profit_and_loss)')
                    ->find();
            }elseif ($tableName=='jinkong_deposit_and_withdrawal'){
                $collect = DB::name($tableName)
                    ->where($where)
                    ->where($where1)
                    ->field('sum(cash_in),sum(cash_out),sum(cash_in_and_out),sum(number_of_gold_entries),sum(gold_output)')
                    ->find();
            }
            $count = DB::name($tableName)
                ->where($where)
                ->where($where1)
                ->count();
        }else{
            $data=[];
        }


        return [
            'data' => $data,
            'count' => $count,
            'collect' => $collect
        ];
    }

  /**
   * 导入表
   * 多出的和不识别的键值会报错，少的键值默认放null
   * 根据传入的数据$data标题，与数据库的注释做判断入表
   * 根据相同的字段注释入表
   * 
   */
  public function import($dbName, $data) 
  {
    // 查询 $dbName 表所有字段和备注
    $columns = Db::query('select COLUMN_NAME,column_comment from INFORMATION_SCHEMA.Columns where table_name="' . $dbName . '" and table_schema="hesuan_admin"');


    // 此时$data没有键值
    // 根据注释为$data添加表中对应的字段名
    foreach ($data as $key => $value) {
      foreach ($value as $k => $v) {
        $verifKey = 0;   // 判断键值规范

        foreach ($columns as $y => $e) {
          // comment->备注
          if ($v['comment'] === $e['column_comment']) {
            $data[$key][$k]['COLUMN_NAME'] = $e['COLUMN_NAME'];
            $verifKey = 1;
            break;
          }
        }

        // 多出数据表设定键值
        if ($verifKey === 0) {
          return [
            'code' => '0',
            'message' => '未识别键 "' . $v['comment'] . '"'
          ];
        }
      }
    }

    $insert = [];
    $insertAll = [];

    // 按照数据表的字段添加到数据库中
    // COLUMN_NAME -> 字段名、 column_comment -> 字段注释
    foreach ($data as $key => $value) {
      foreach ($value as $k => $v) {
        if (array_key_exists('COLUMN_NAME', $v)) {
          $insert[$v['COLUMN_NAME']] = $v['value'];
        }
      }

      array_push($insertAll, $insert);
      $insert = [];
    }


    Db::startTrans();
    try {
      $num = Db::name($dbName)->data($insertAll)->limit(100)->insertAll();
      Db::commit();
    } catch (\Exception $e) {
      // 这是进行异常捕获
      $errMeg = $e->getMessage();
      Db::rollback();

      if (strpos($errMeg, 'violation') && strpos($errMeg, 'Duplicate') && strpos($errMeg, 'key')) {
        $errMeg = '重复主键';
      }

      return [
        'code' => '0',
        'message' => $errMeg
      ];
    }
    


    return [
      'code' => '1',
      'num' => $num
    ];
	}

    /**
     * @param $tableName
     * 查询表数据
     */
    public function getTableDate($tableName) {
        $tableDate=Db::name($tableName)->select();
        return $tableDate;
    }
}
