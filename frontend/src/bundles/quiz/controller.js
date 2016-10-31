'use strict';

var VqQuizControllers = angular.module('VqQuizControllers', ['VqService', 'VqQuizServices']);

VqQuizControllers.controller('vqQuestionController', [
        '$scope',
        '$state',
        'vqQuizService',
        'vqQuizCurrentQuestion',
        function ($scope, $state, vqQuizService, vqQuizCurrentQuestion) {
            var inAnsweringInProcess;

            startQuiz();

            // ---------------------------------------------------------------------------------------------------------
            // Public methods
            // ---------------------------------------------------------------------------------------------------------

            $scope.renderIncorrectAnswer = false;

            $scope.answer = function (answerId) {
                if (inAnsweringInProcess) {
                    return;
                }

                $scope.renderIncorrectAnswer = false;
                inAnsweringInProcess = true;
                vqQuizCurrentQuestion
                    .answer(vqQuizService.getCurrentQuizId(), answerId)
                    .then(function (response) {
                        if (response.is_finished) {
                            $state.go('result');

                            return;
                        }

                        if (response.is_answer_right) {
                            getNextQuestion();
                        } else {
                            $scope.renderIncorrectAnswer = true;
                        }
                        inAnsweringInProcess = false;
                    });
            };

            // ---------------------------------------------------------------------------------------------------------
            // Private methods
            // ---------------------------------------------------------------------------------------------------------

            function startQuiz() {
                vqQuizService
                    .createQuiz()
                    .then(function (response) {
                        vqQuizService.setCurrentQuizId(response.id);
                        inAnsweringInProcess = false;

                        getNextQuestion();
                    });
            }

            function getNextQuestion() {
                vqQuizCurrentQuestion.setupCurrentQuestion(vqQuizService.getCurrentQuizId()).then(function () {
                    $scope.question = vqQuizCurrentQuestion.getQuestion();
                    $scope.possibleAnswers = vqQuizCurrentQuestion.getPossibleAnswers();
                    $scope.renderIncorrectAnswer = false;
                });
            }
        }
    ]
);

VqQuizControllers.controller('vqResultController', [
        '$scope',
        '$state',
        '$stateParams',
        'vqQuizService',
        function ($scope, $state, $stateParams, vqQuizService) {
            initialize();

            // ---------------------------------------------------------------------------------------------------------
            // Private methods
            // ---------------------------------------------------------------------------------------------------------

            function initialize() {
                console.log(vqQuizService.getCurrentQuizId());
                if (!vqQuizService.getCurrentQuizId()) {
                    $state.transitionTo('login');

                    return;
                }

                vqQuizService
                    .getQuizResult(vqQuizService.getCurrentQuizId())
                    .then(function (response) {
                        $scope.score = response.score;
                    });
            }
        }
    ]
);