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

            // Find the real TaskItem from the ExamAttempt's Exam structure
            $targetCode = $answer->getTaskCode()->getValue();
            $taskItem = null;

            // Inefficient but safe MVP traversal
            foreach ($attempt->getExam()->getTaskGroups() as $group) {
                foreach ($group->getItems() as $item) {
                    if ($item->getCode()->getValue() === $targetCode) {
                        $taskItem = $item;
                        break 2;
                    }
                }
            }

            if (!$taskItem) {
                continue; // Or throw exception? Skip for now.
            }

            $maxPoints = $taskItem->getMaxPoints();

            $payload = $answer->getPayload();
            // Simplify payload processing (string or array)
            $content = is_array($payload) ? (string)json_encode($payload) : (string)$payload;

            $score = (strlen($content) > 10) ? $maxPoints : 0;

            $evaluation = TaskEvaluation::createAi(
                $attempt,
                $taskItem,
                $score,
                'gpt-4-mock',
                "Mock AI Feedback: Answer length was " . strlen($content) . ". Score assigned: $score/$maxPoints."
            );

            $attempt->recordEvaluation($evaluation);
        }

        $attempt->finishGrading();
    }
}
