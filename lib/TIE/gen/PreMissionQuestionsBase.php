<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class PreMissionQuestionsBase extends PyriteBase implements Byteable {
	use HexDecoder;

	public $PreMissionQuestionsLength = 0;

	/** @var SHORT */
	public $Length;
	/** @var CHAR<QuestionLength()> */
	public $Question;
	/** @var BYTE */
	public $Reserved; // (0xA)
	/** @var CHAR<AnswerLength()> */
	public $Answer;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->Length = $this->getShort($hex, 0x0);
		if ($this->Length === 0) {
			$this->afterConstruct();
			return;
		}
		$this->Question = $this->getChar($hex, 0x2, $this->QuestionLength());
		$offset = 0x2;
		$offset += $this->QuestionLength();
		$this->Reserved = $this->getByte($hex, $offset);
		$offset += 1;
		$this->Answer = $this->getChar($hex, $offset, $this->AnswerLength());
		$offset += $this->AnswerLength();
		$this->PreMissionQuestionsLength = $offset;
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"Length" => $this->Length,
			"Question" => $this->Question,
			"Reserved" => $this->Reserved,
			"Answer" => $this->Answer		];
	}

	abstract protected function QuestionLength();

	abstract protected function AnswerLength();

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeShort($hex, $this->Length, 0x0);
		if ($this->Length === 0) {
			return;
		}
		$this->writeChar($hex, $this->Question, 0x2, $this->QuestionLength());
		$offset = 0x2;
		$offset += $this->QuestionLength();
		$this->writeByte($hex, $this->Reserved, $offset);
		$offset += 1;
		$this->writeChar($hex, $this->Answer, $offset, $this->AnswerLength());
		$offset += $this->AnswerLength();
		return $hex;
	}


    public function getLength(){
        return $this->PreMissionQuestionsLength;
    }
}