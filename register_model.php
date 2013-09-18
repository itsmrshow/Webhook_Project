<?php
class Register_model extends CI_Model {
	
	private $_exsOptions =<<<END
<option value="0">--</option>
<option value="011">011</option>
<option value="012">012</option>
<option value="131">131</option>
<option value="158">158</option>
<option value="184">184</option>
<option value="185">185</option>
<option value="191">191</option>
<option value="202">202</option>
<option value="203">203</option>
<option value="212">212</option>
<option value="227">227</option>
<option value="280">280</option>
<option value="281">281</option>
<option value="282">282</option>
<option value="283">283</option>
<option value="286">286</option>
<option value="288">288</option>
<option value="289">289</option>
<option value="291">291</option>
<option value="292">292</option>
<option value="293">293</option>
<option value="301">301</option>
<option value="302">302</option>
<option value="303">303</option>
<option value="308">308</option>
<option value="328">328</option>
<option value="350">350</option>
<option value="352">352</option>
<option value="380">380</option>
<option value="383">383</option>
<option value="384">384</option>
<option value="386">386</option>
<option value="388">388</option>
<option value="389">389</option>
<option value="394">394</option>
<option value="400">400</option>
<option value="402">402</option>
<option value="403">403</option>
<option value="411">411</option>
<option value="421">421</option>
<option value="442">442</option>
<option value="480">480</option>
<option value="483">483</option>
<option value="485">485</option>
<option value="490">490</option>
<option value="495">495</option>
<option value="497">497</option>
<option value="499">499</option>
<option value="552">552</option>
<option value="554">554</option>
<option value="558">558</option>
<option value="570">570</option>
<option value="572">572</option>
<option value="573">573</option>
<option value="574">574</option>
<option value="578">578</option>
<option value="579">579</option>
<option value="583">583</option>
<option value="590">590</option>
<option value="591">591</option>
<option value="600">600</option>
END;

	private $_creditOptions =<<<END
<option value=".5">.5</option>
<option value="1">1</option>
<option value="1.5">1.5</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
END;

	public function set_registration() {
		
		
		$date = $this->input->post('date');
		$advisor = $this->input->post('advisor');
		$student = $this->input->post('student');
		$gpa = $this->input->post('gpa');
		$accept = $this->input->post('accept');
		$semester = $this->input->post('season') . " - " . $this->input->post('semester');
		$id = $this->input->post('id');
		$pin = $this->input->post('pin');
		$cssemester = $this->input->post('csseason') . " - " . $this->input->post('csyear');
		$csyear = $this->input->post('csyear');
		$csseason = $this->input->post('csseason');
		$exs = $this->input->post('exs');
		$course = $this->input->post('course');
		$credit = $this->input->post('credit');
		$crn = $this->input->post('crn');
		$days = $this->input->post('days');
		$times = $this->input->post('times');
		$cnexs =$this->input->post('cnexs');
		$cn2014 = $this->input->post('cn2014');
		$cr2014 = $this->input->post('cr2014');
		$cnfexs = $this->input->post('cnfexs');
		$cnf2014 = $this->input->post('cnf2014');
		$crf2014 = $this->input->post('crf2014');
		$studentNotes = $this->input->post('studentNotes');
		$chairNotes = $this->input->post('chairNotes');
		$pre1 = $this->input->post('pre1season') . ' - ' . $this->input->post('pre1year');
		$pre1year = $this->input->post('pre1year');
		$pre1season = $this->input->post('pre1season');
		$pre2 = $this->input->post('pre2season') . ' - ' . $this->input->post('pre2year');
		$pre2year = $this->input->post('pre2year');
		$pre2season = $this->input->post('pre2season');
		
		$studentInsert = array(
			"student_id" => $id,
			"reg_date" => date("Y-m-d"),
			"advisor" => $advisor,
			"student" => $student,
			"gpa" => $gpa,
			"accept" => $accept == "yes" ? 1 : 0,
			"semester" => $semester,
			"pin" => $pin
		);
		
		$query_string = $this->db->insert_string("student", $studentInsert);
		
		$this->db->query($query_string.' ON DUPLICATE KEY UPDATE student_id = student_id');
		
		$dom = new DOMDocument();
		
		$dom->load('registration.xml');
		
		$root = $dom->getElementsByTagName('reg')->item(0);
		
		$newItem = $dom->createElement('registration');
		
		$dateSection = $newItem->appendChild($dom->createElement('date'));
		$dateData = $dom->createCDATASection($date);
		$dateSection->appendChild($dateData);
		
		$advisorSection = $newItem->appendChild($dom->createElement('advisor'));
		$advisorData = $dom->createCDATASection($advisor);
		$advisorSection->appendChild($advisorData);
		
		$studentSection = $newItem->appendChild($dom->createElement('student'));
		$studentData = $dom->createCDATASection($student);
		$studentSection->appendChild($studentData);
		
		$newItem->appendChild($dom->createElement('accept', $accept));
		$newItem->appendChild($dom->createElement('gpa', $gpa));
		$newItem->appendChild($dom->createElement('semester', $semester));
		$newItem->appendChild($dom->createElement('id', $id));
		$newItem->appendChild($dom->createElement('pin', $pin));
		$newItem->appendChild($dom->createElement('csSemester', $cssemester));
		
		$courseSection = $newItem->appendChild($dom->createElement('courses'));
		
		for($xy = 0; $xy < count($exs); $xy++) {
			if($exs[$xy] != "0" || trim($course[$xy]) != "") {
				$cs = $courseSection->appendChild($dom->createElement('course'));
				
				$cs->appendChild($dom->createElement('exs', $exs[$xy]));
				
				$cns = $cs->appendChild($dom->createElement('name'));
				$cdata = $dom->createCDATASection($course[$xy]);
				$cns->appendChild($cdata);
				
				$cs->appendChild($dom->createElement('credit', $credit[$xy]));
				
				$cs->appendChild($dom->createElement('crn', $crn[$xy]));
				
				$ds = $cs->appendChild($dom->createElement('days'));
				$dsData = $dom->createCDATASection($days[$xy]);
				$ds->appendChild($dsData);
				
				$ts = $cs->appendChild($dom->createElement('times'));
				$tsData = $dom->createCDATASection($times[$xy]);
				$ts->appendChild($tsData);
				
				$exsInsert = array(
					"student_id" => $id,
					"year" => $csyear,
					"semester" => $csseason, 
					"exs" => $exs[$xy]
				);
				
				$query_string = $this->db->insert_string("student_exs", $exsInsert);
				
				$this->db->query($query_string.' ON DUPLICATE KEY UPDATE student_id = student_id');
			}
		}
		
		$s14s = $newItem->appendChild($dom->createElement('spring2014'));
		
		for($xy = 0; $xy < count($cnexs); $xy++) {
			if($cnexs[$xy] != "0" || trim($cn2014[$xy]) != "") {
				$ss = $s14s->appendChild($dom->createElement('course'));
				
				$ss->appendChild($dom->createElement('semester', $pre1));
				
				$ss->appendChild($dom->createElement('exs',$cnexs[$xy]));
				
				$sss = $ss->appendChild($dom->createElement('name'));
				$ssData = $dom->createCDATASection($cn2014[$xy]);
				$sss->appendChild($ssData);
				
				$ss->appendChild($dom->createElement('credit', $cr2014[$xy]));
				
				$exsInsert = array(
					"student_id" => $id,
					"year" => $pre1year,
					"semester" => $pre1season, 
					"exs" => $cnexs[$xy]
				);
				
				$query_string = $this->db->insert_string("student_exs", $exsInsert);
				
				$this->db->query($query_string.' ON DUPLICATE KEY UPDATE student_id = student_id');
			}
		}
		
		$f14s = $newItem->appendChild($dom->createElement('fall2014'));
		
		for($xy = 0; $xy < count($cnfexs); $xy++) {
			if($cnfexs[$xy] != "0" || trim($cnf2014[$xy]) != "") {
				$sf = $f14s->appendChild($dom->createElement('course'));
				
				$sf->appendChild($dom->createElement('semester', $pre2));
				
				$sf->appendChild($dom->createElement('exs', $cnfexs[$xy]));
				
				$sfs = $sf->appendChild($dom->createElement('name'));
				$sfData = $dom->createCDATASection($cnf2014[$xy]);
				$sfs->appendChild($sfData);
				
				$sf->appendChild($dom->createElement('credit', $crf2014[$xy]));
				
				$exsInsert = array(
					"student_id" => $id,
					"year" => $pre2year,
					"semester" => $pre2season, 
					"exs" => $cnfexs[$xy]
				);
				
				$query_string = $this->db->insert_string("student_exs", $exsInsert);
				
				$this->db->query($query_string.' ON DUPLICATE KEY UPDATE student_id = student_id');
			}
		}
		
		$sns = $newItem->appendChild($dom->createElement('student_notes'));
		$snsData = $dom->createCDATASection($studentNotes);
		$sns->appendChild($snsData);
		
		$cns = $newItem->appendChild($dom->createElement('chair_notes'));
		$cnsData = $dom->createCDATASection($chairNotes);
		$cns->appendChild($cnsData);
		
		$root->appendChild($newItem);
		
		$xml = $dom->save('registration.xml');
	}

	public function getExs() {
		return $this->_exsOptions;
	}
	
	public function getCredit() {
		return $this->_creditOptions;
	}
}
?>