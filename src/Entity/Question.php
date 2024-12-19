<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $questionText = null;

    #[ORM\Column(length: 255)]
    private ?string $answerOne = null;

    #[ORM\Column(length: 255)]
    private ?string $answerTwo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $answerThree = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $answerFour = null;

    #[ORM\Column(length: 1)]
    private ?string $correctAnswer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $beginTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endTime = null;

    /**
     * @var Collection<int, Answer>
     */
    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'question', orphanRemoval: true)]
    private Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function setQuestionText(string $questionText): static
    {
        $this->questionText = $questionText;

        return $this;
    }

    public function getAnswerOne(): ?string
    {
        return $this->answerOne;
    }

    public function setAnswerOne(string $answerOne): static
    {
        $this->answerOne = $answerOne;

        return $this;
    }

    public function getAnswerTwo(): ?string
    {
        return $this->answerTwo;
    }

    public function setAnswerTwo(string $answerTwo): static
    {
        $this->answerTwo = $answerTwo;

        return $this;
    }

    public function getAnswerThree(): ?string
    {
        return $this->answerThree;
    }

    public function setAnswerThree(string $answerThree): static
    {
        $this->answerThree = $answerThree;

        return $this;
    }

    public function getAnswerFour(): ?string
    {
        return $this->answerFour;
    }

    public function setAnswerFour(?string $answerFour): static
    {
        $this->answerFour = $answerFour;

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(string $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    public function getBeginTime(): ?\DateTimeInterface
    {
        return $this->beginTime;
    }

    public function setBeginTime(\DateTimeInterface $beginTime): static
    {
        $this->beginTime = $beginTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }
}
