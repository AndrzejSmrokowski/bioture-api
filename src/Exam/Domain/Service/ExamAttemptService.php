<?php

namespace Bioture\Exam\Domain\Service;

use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Bioture\Exam\Domain\Model\Exam;
use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Model\StudentAnswer;
use Bioture\Exam\Domain\Model\Task;
use Doctrine\ORM\EntityManagerInterface;

class ExamAttemptService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AICheckerInterface $aiChecker
    ) {
    }

    public function startExam(Exam $exam): ExamAttempt
    {
        $attempt = new ExamAttempt($exam);
        $this->entityManager->persist($attempt);
        $this->entityManager->flush();

        return $attempt;
    }

    // Usually you'd submit all answers at once or one by one.
    // For simplicity, let's assume we can add/update answer.
    public function saveAnswer(ExamAttempt $attempt, Task $task, string $content): StudentAnswer
    {
        // Check if answer exists
        $existing = null;
        foreach ($attempt->getAnswers() as $ans) {
            if ($ans->getTask()->getId() === $task->getId()) {
                $existing = $ans;
                break;
            }
        }

        if ($existing) {
            $existing->setAnswerContent($content);
            $answer = $existing;
        } else {
            $answer = new StudentAnswer($attempt, $task);
            $answer->setAnswerContent($content);
            $this->entityManager->persist($answer);
            // $attempt->addAnswer($answer); // Updates collection side
        }

        $this->entityManager->flush();
        return $answer;
    }

    public function submitExam(ExamAttempt $attempt): void
    {
        $attempt->setSubmittedAt(new \DateTimeImmutable());
        $attempt->setStatus(ExamAttemptStatus::SUBMITTED);

        // Trigger AI Check Immediately for this workflow
        $this->aiChecker->checkAttempt($attempt);

        $this->entityManager->flush();
    }
}
