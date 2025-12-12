<?php

namespace Bioture\Exam\Infrastructure\Service;

use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Bioture\Exam\Domain\Service\AICheckerInterface;

class MockAIChecker implements AICheckerInterface
{
    public function checkAttempt(ExamAttempt $attempt): void
    {
        // Simulate checking
        foreach ($attempt->getAnswers() as $answer) {
            // Mock logic: if response length > 5, full points :D
            // In real world, call OpenAI API here.

            $maxPoints = $answer->getTask()->getMaxPoints();

            $content = $answer->getAnswerContent();
            $score = (strlen($content ?? '') > 10) ? $maxPoints : 0;

            $answer->setScore($score);
            $answer->setFeedback("Mock AI Feedback: Answer length was " . strlen($content ?? '') . ". Score assigned: $score/$maxPoints.");
        }

        $attempt->setCheckedAt(new \DateTimeImmutable());
        $attempt->setStatus(ExamAttemptStatus::CHECKED);
    }
}
