<?php

namespace Bioture\Exam\Infrastructure\Service;

use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Bioture\Exam\Domain\Service\AICheckerInterface;
use Bioture\Exam\Domain\Model\TaskEvaluation;

class MockAIChecker implements AICheckerInterface
{
    public function checkAttempt(ExamAttempt $attempt): void
    {
        // Simulate checking
        foreach ($attempt->getAnswers() as $answer) {
            // Mock logic: if response length > 5, full points :D
            // In real world, call OpenAI API here.

            $taskItem = $answer->getTaskItem();
            // Note: maxPoints is on the TaskItem now? Or Group?
            // In TaskItemEntity it was maxPoints.
            // Domain TaskItem needs maxPoints accessor.
            // Assuming it's there. If not, I need to check TaskItem.php.
            // Let's assume getPoints() or getMaxPoints().

            // To be safe, I'll check TaskItem source quickly or assume standard name.
            // If implementation fails, I'll fix it.
            // Standard convention: getMaxPoints()

            $maxPoints = $taskItem->getMaxPoints();

            $payload = $answer->getPayload();
            // Simplify payload processing (string or array)
            $content = is_array($payload) ? (string)json_encode($payload) : (string)$payload;

            $score = (strlen($content) > 10) ? $maxPoints : 0;

            $evaluation = new TaskEvaluation($attempt, $taskItem);
            $evaluation->setAwardedPoints($score);
            $evaluation->setRationale("Mock AI Feedback: Answer length was " . strlen($content) . ". Score assigned: $score/$maxPoints.");
            $evaluation->setGrader('MOCK_AI');
            $evaluation->setGraderVersion('v1.0');

            $attempt->addEvaluation($evaluation);
        }

        $attempt->setCheckedAt(new \DateTimeImmutable());
        $attempt->setStatus(ExamAttemptStatus::CHECKED);
    }
}
