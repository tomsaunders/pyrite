<?php
namespace Pyrite\TIE;

use Pyrite\Summary;

class PostMissionQuestions extends PostMissionQuestionsBase implements Summary
{
    protected function afterConstruct()
    {
        if ($this->Length === 0) {
            $this->PostMissionQuestionsLength = 2;
        }
    }

    protected function QuestionLength()
    {
        if ($this->Length === 0) {
            return 0;
        }
        $text = substr($this->hex, 4, $this->Length);
        if (strpos($text, chr(10))) {
            list($question) = explode(chr(10), $text, 2);
            return strlen($question);
        }
    }

    protected function AnswerLength()
    {
        if ($this->Length === 0) {
            return 0;
        }
        $text = substr($this->hex, 4, $this->Length - 2);
        if (strpos($text, chr(10))) {
            list(, $answer) = explode(chr(10), $text, 2);
            return strlen($answer);
        }
    }

	public function summaryHash(){
		if ($this->Length === 0) {
			return false;
		}
		return [
			'Type' => $this->getQuestionTypeLabel(),
			'Condition' => $this->getQuestionConditionLabel(),
			'Question' => $this->Question,
			'Answer' => $this->Answer
		];
	}
}
