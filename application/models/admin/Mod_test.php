<?php 
/**
* 
*/
class mod_test extends CI_Model
{
	
	function __construct()
	{
		# code...
	}
	public function get_settings($symbol)
	{	
		$this->db->where('coin_symbol',$symbol);
		$candle_sett = $this->db->get('candle_settings');
		$candel = $candle_sett->row_array();
		return $candel;
	}
	public function save_setting($data)
	{
		extract($data);
		if (!$enableBarColors) {
			$enableBarColors = 0;
		}
		if (!$use2Bars) {
			$use2Bars = 0;
		}
		if (!$lowVol) {
			$lowVol = 0;
		}
		if (!$climaxUp) {
			$climaxUp = 0;
		}
		if (!$climaxDown) {
			$climaxDown = 0;
		}
		if (!$churn) {
			$churn = 0;
		}
		if (!$climaxChurn) {
			$climaxChurn = 0;
		}
		if (!$chck_white) {
			$chck_white = 0;
		}
		if (!$climaxDown1) {
			$climaxDown1 = 0;
		}
		if (!$churn1) {
			$churn1 = 0;
		}
		if (!$climaxChurn1) {
			$climaxChurn1 = 0;
		}
		if (!$ShowHHLL) {
			$ShowHHLL = 0;
		}
		if (!$WaitForClose) {
			$WaitForClose = 0;
		}
		$upd_arr = array(
			'coin_symbol' => $coin,
			'warning_limit' => $warning_limit,
			'candle_seconds' => $candle_seconds,
			'vol_ma_size'	=> $vol_ma_size,
			'look_back'		=> $lookback,
			'enable_bar_color' => $enableBarColors,
			'use_2_bar'	=> $use2Bars,
			'low_vol'	=> $lowVol,
			'climax_up'	=> $climaxUp,
			'climax_down' => $climaxDown,
			'churn' => $churn,
			'climax_churn' => $climaxChurn,
			'pline_100' => $pline,
			'time_7'	=> $tline,
			'chck_white' => $chck_white,
			'climaxDown1' => $climaxDown1,
			'churn1' => $churn1,
			'climaxChurn1' => $climaxChurn1,
			'ShowHHLL' => $ShowHHLL,
			'WaitForClose' => $WaitForClose,
			'pvtLenL' => $pvtLenL,
			'pvtLenR' => $pvtLenR,
			'maxLvlLen' => $maxLvlLen,
			'PercentileTrigger' => $PercentileTrigger,
			'DemandTrigger' => $DemandTrigger,
			'SupplyTrigger' => $SupplyTrigger,
			'BarsBack' => $BarsBack,
			'Current_Down_Percentile' => $Current_Down_Percentile,
			'Current_Down_Percentile_supply' => $Current_Down_Percentile_supply,
			'Continuation_Down_Percentile' => $Continuation_Down_Percentile,
			'Continuation_Down_Percentile_supply' => $Continuation_Down_Percentile_supply,
			'Current_up_Percentile' => $Current_up_Percentile,
			'Current_up_Percentile_supply' => $Current_up_Percentile_supply,
			'Continuation_up_Percentile' => $Continuation_up_Percentile,
			'Continuation_up_Percentile_supply' => $Continuation_up_Percentile_supply,
			'LH_Percentile' => $LH_Percentile,
			'LH_Percentile_supply' => $LH_Percentile_supply,
			'HL_Percentile' => $HL_Percentile,
			'HL_Percentile_supply' => $HL_Percentile_supply
		);

		$this->db->where('coin_symbol', $coin);
		$candle_sett = $this->db->get('candle_settings');
		$candel = $candle_sett->row_array();

		if (count($candel) == 0) {
			$this->db->dbprefix('candle_settings');
			$ins = $this->db->insert('candle_settings',$upd_arr);
		}
		else
		{
			$this->db->dbprefix('candle_settings');
			$this->db->where('id' , $candel['id']);
			$ins = $this->db->update('candle_settings',$upd_arr);
		}
		return true;	
	}
}
?>