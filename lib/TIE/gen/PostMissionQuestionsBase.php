<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class PostMissionQuestionsBase extends PyriteBase implements Byteable {
	use HexDecoder;

	public $PostMissionQuestionsLength = 0;

	/** @var SHORT */
	public $Length;
	/** @var BYTE */
	public $QuestionCondition;
	/** @var BYTE */
	public $QuestionType;
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
		$this->QuestionCondition = $this->getByte($hex, 0x2);
		$this->QuestionType = $this->getByte($hex, 0x3);
		$this->Question = $this->getChar($hex, 0x4, $this->QuestionLength());
		$offset = 0x4;
		$offset += $this->QuestionLength();
		$this->Reserved = $this->getByte($hex, $offset);
		$offset += 1;
		$this->Answer = $this->getChar($hex, $offset, $this->AnswerLength());
		$offset += $this->AnswerLength();
		$this->PostMissionQuestionsLength = $offset;
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"Length" => $this->Length,
			"QuestionCondition" => $this->getQuestionConditionLabel(),
			"QuestionType" => $this->getQuestionTypeLabel(),
			"Question" => $this->Question,
			"Reserved" => $this->Reserved,
			"Answer" => $this->Answer		];
	}

        protected function getQuestionConditionLabel() {
            return isset($this->QuestionCondition) && isset(Constants::$QUESTIONCONDITION[$this->QuestionCondition]) ? Constants::$QUESTIONCONDITION[$this->QuestionCondition] : "Unknown";
        }

        protected function getQuestionTypeLabel() {
            return isset($this->QuestionType) && isset(Constants::$QUESTIONTYPE[$this->QuestionType]) ? Constants::$QUESTIONTYPE[$this->QuestionType] : "Unknown";
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
		$this->writeByte($hex, $this->QuestionCondition, 0x2);
		$this->writeByte($hex, $this->QuestionType, 0x3);
		$this->writeChar($hex, $this->Question, 0x4, $this->QuestionLength());
		$offset = 0x4;
		$offset += $this->QuestionLength();
		$this->writeByte($hex, $this->Reserved, $offset);
		$offset += 1;
		$this->writeChar($hex, $this->Answer, $offset, $this->AnswerLength());
		$offset += $this->AnswerLength();
		return $hex;
	}


    public function getLength(){
        return $this->PostMissionQuestionsLength;
    }
}