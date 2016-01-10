/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


studentApp.controller('Lesson', ['$scope', '$http', '$upload', '$location', '$anchorScroll', '$stateParams', function ($scope, $http, $upload, $location, $anchorScroll, $route) {
        /* Init Params */
        $scope.errors      = {},
        $scope.res         = {},
        //For Lang
        $scope._l          = _l,
        $scope.customColor = '#f1f0f0';
        
        //
        $scope.lessonAnswer = {
            draft   : 0,
            name    : '',
            insight : '',
            custom_color : ''
        },
        //
        $scope.lesson = {
            id : 0,
            name: '',
            description: '',
            class : '',
            subject:  '',
            deadLine: '',
            guide: '',
            completed: false,
            tasks: []
        },
        
        //Answer Colors
        $scope.colors = {
            type: "select",
            name: "color",
            values: [ "#fc611f", "#fdc10b", "#a6dae8", "#14b0bf", "#2e7980", "#cd6db3", "#8208C0", "#23AE89", "#E94B3B" ]
        },
        
        //
        $scope.saveName = function ($name){
            if( $name ){
                $scope.lessonAnswer.name = $name;
            }
	},
        //
	$scope.saveInsight = function ($insight){
            if( $insight ){
                $scope.lessonAnswer.insight = $insight;
            }
	},
        //
	$scope.saveColor = function ($color){
            $scope.lessonAnswer.custom_color = $color ? $color : '';
	},
        //
        $scope.showAnswerText = function ($index) {
            $scope.lesson.tasks[$index].edit = true;
        },
        //
        $scope.hideAnswerText = function ($index) {
            if( $scope.lesson.tasks[$index].answer.text == ''){
                $scope.lesson.tasks[$index].edit = false;
            }
        },
        //
        $scope.saveAnswerText = function($index, $text) {
            if($scope.lesson.tasks[$index].edit ) {
                $scope.lesson.tasks[$index].answer.text = $text ? $text : '';
            } else {
                $scope.lesson.tasks[$index].edit = false;
            }
	},
        //
	$scope.saveAnswerLink = function ($index, $link){
            $scope.lesson.tasks[$index].answer.link = $link ? $link : '';
	},
        //Store Selected Files
        $scope.onFileSelect = function($files, $index) {
            $scope.lesson.tasks[$index].answer.file = $files;
        },
        //
        $scope.saveImg = function($index) {
            document.getElementById('file' + $index + '').addEventListener('change', function(e){
                $scope.showImg(e, $index);
            }, false);
	},
        //
        $scope.showImg = function(e, $index) {
            var file = e.target.files[0];
            if( file.type.match('image/*')) {
                var reader = new FileReader();
                reader.onload = (function() {
                    return function(e) {
                        $scope.lesson.tasks[$index].answer.img = e.target.result;
                        $scope.$apply();
                    };
                })(file);
                reader.readAsDataURL(file);
            } else { //pdf, word, ppt
                if( file.type.match('application/pdf') ){
                    $scope.lesson.tasks[$index].answer.img = '';
                    $scope.lesson.tasks[$index].answer.fileIcon = "PDF";
                }
                if( file.type.match('application/vnd.openxmlformats-officedocument.wordprocessingml.document') ){
                    $scope.lesson.tasks[$index].answer.img = '';
                    $scope.lesson.tasks[$index].answer.fileIcon = "WORD";
                }
                if( file.type.match('application/vnd.openxmlformats-officedocument.presentationml.presentation') ){
                    $scope.lesson.tasks[$index].answer.img = '';
                    $scope.lesson.tasks[$index].answer.fileIcon = "POWERPOINT";
                }
                $scope.$apply();
                console.log(file.type);
            }
	},
        //
        $scope.ediTask = function ($index) {
            $scope.lesson.tasks[$index].done = false;
        },
        //
        $scope.attachsToggle = function ($index) {
            $scope.lesson.tasks[$index].customAttachs = !$scope.lesson.tasks[$index].customAttachs;
            
            attc = $( '.attachments-' + $index );
            height = ($scope.lesson.tasks[$index].customLink) ? 25 : 0;
            height += ( $scope.lesson.tasks[$index].customFile) ? 25 : 0;

            if( !$scope.lesson.tasks[$index].customAttachs ){
                attc.css( 'bottom', -height - 30 - 15 );
                attc.parent().css( 'margin-bottom', height + 30 + 30 );
            } else {
                attc.parent().css( 'margin-bottom', 30 );
            }
        },
        //Is Task Done?
        $scope.isDone = function ($index) {
            var task = $scope.lesson.tasks[$index];
            if ( task.answer.text != '' && (task.answer.file != '' || task.answer.img != '') ){
                $scope.lesson.tasks[$index].done = true;
                if($scope.lesson.tasks[$index].customAttachs == false){
                    $scope.attachsToggle($index);
                }
            } else if ( !task.attach && task.answer.text != '' ){
                $scope.lesson.tasks[$index].done = true;
                if($scope.lesson.tasks[$index].customAttachs == false){
                    $scope.attachsToggle($index);
                }
            }
        },
        //
	$scope.check = function(){
            var valid               = true;
            $scope.errors.HWName    = 0;
            $scope.errors.HWInsight = 0;
            $scope.errors.HWTasks   = 0;
            
            if( !$scope.lessonAnswer.name ){
                $scope.errors.HWName = 1;
                valid = false;
            }
            if( !$scope.lessonAnswer.insight ){
                $scope.errors.HWInsight = 1;
                valid = false;
            }
            if( !$scope.isCompleted() ){
                $scope.errors.HWTasks = 1;
                valid = false;
            }
            
            if( !valid ) return false;
            return true;
	},
        //Is Lesson Completed?
	$scope.isCompleted = function(){
            var $counter = 0;
            for (var i = 0 ; i < $scope.lesson.tasks.length ; i++) {
                if($scope.lesson.tasks[i].done){
                    $counter++;
                }
            }
            if($counter == $scope.lesson.tasks.length) {
                $scope.lesson.completed = true;
                return true;
            } else{
                return false;
            }
	},
        
        //
        $scope.populateLesson = function($lesson, $tasks){
            //Populate Lesson Info
            $scope.lesson.id          = $lesson.lesson_id;
            $scope.lesson.name        = $lesson.name;
            $scope.lesson.description = $lesson.description;
            $scope.lesson.class       = $lesson.class;
            $scope.lesson.subject     = $lesson.subject;
            $scope.lesson.deadLine    = $lesson.dead_line;
            //Populate Tasks Of Lesson
            for(var i = 0; i < $tasks.length; i++){
                var task = [];
                task.id              = $tasks[i].task_id;
                task.name            = $tasks[i].name;
                task.type            = $tasks[i].type;
                task.img             = $tasks[i].img;
                task.attach          = ($tasks[i].type == 'questionnaire' || $tasks[i].type == 'info') ? false : true;
                task.color           = $tasks[i].color;
                task.guide           = $tasks[i].guide;
                task.cet             = $tasks[i].cet;
                task.google          = $tasks[i].google;
                task.customLink      = $tasks[i].custom_link;
                task.customFile      = $tasks[i].custom_file;
                task.customAttachs   = ($tasks[i].custom_link || $tasks[i].custom_file) ? true : false;
                task.colorText       = 'YELLOW';
                task.done            = false;
                task.answer          = [];
                task.answer.img      = '';
                task.answer.text     = '';
                task.answer.link     = '';
                task.answer.file     = '';
                task.answer.fileIcon = '';
                $scope.lesson.tasks.push(task);             
            }
        },
        
        //
        $scope.saveAsDraft = function () {
            $scope.lessonAnswer.draft = 1;
        },
        //
        $scope.submitHomeWork = function () {
            //check is completed??
            if( $scope.check() ){
                var lessonId        = $scope.lesson.id;
                var draft           = $scope.lessonAnswer.draft;
                var homeWorkName    = $scope.lessonAnswer.name;
                var homeWorkInsight = $scope.lessonAnswer.insight;
                var homeWorkColor   = $scope.lessonAnswer.custom_color;
                
                $scope.upload = $upload.upload({
                    url: 'student/homework',
                    method: 'POST',
                    headers: {'header-key': 'header-value'},
                    withCredentials: true,
                    data: { 
                        'lesson_id'    : parseInt(lessonId),
                        'draft'        : parseInt(draft),
                        'name'         : homeWorkName,
                        'insight'      : homeWorkInsight,
                        'custom_color' : homeWorkColor
                    }
                })
                .success(function(data, status, headers, config) {
                    // Get lessonId And Complete insert tasks
                    $scope.submitAnswers( data.homeworkId );
                })
                .error(function(data){
                    console.log('error: ' + data);
                });
            }
        },
        //
        $scope.submitAnswers = function ( $homeworkId ) {
            for (var i = 0; i < $scope.lesson.tasks.length; i++) {
                var lessonId = $scope.lesson.id;
                var taskId   = $scope.lesson.tasks[i].id;
                var textAns  = $scope.lesson.tasks[i].answer.text;
                var linkAns  = $scope.lesson.tasks[i].answer.link;
                var fileAns  = $scope.lesson.tasks[i].answer.file;

                $scope.upload = $upload.upload({
                    url: 'student/answer',
                    method: 'POST',
                    headers: {'header-key': 'header-value'},
                    withCredentials: true,
                    data: { 'lesson_id'   : parseInt(lessonId),
                            'homework_id' : parseInt($homeworkId),
                            'task_id'     : parseInt(taskId),
                            'text'        : textAns,
                            'link'        : linkAns
                        },
                    file: fileAns
                })
                .success(function(data, status, headers, config) {
                    // file is uploaded successfully
                    console.log(data.status + ': ' + data.msg);
                    $location.hash('top');
                    $anchorScroll();
                    $scope.res.isSuccessSubmit = true;
                    $scope.res.successSubmit   = _l.SUCCESS_HOMEWORK_SUBMIT;
                })
                .error(function(data){
                    console.log('error: ' + data);
                    $location.hash('top');
                    $anchorScroll();
                    $scope.res.isErrorSubmit = true;
                    $scope.res.errorSubmit   = _l.FAILE_HOMEWORK_SUBMIT;
                }).then(function () {
                    setTimeout( function () {
                        //$scope.lessonForm = {};
                        window.location = '/student';
                    }, 3000);
                });
            }
        },
        //
        $http.get('student/getlesson', {
            params: {
                    lesson_id : $route.lessonId
                }})
                .success(function(data){
                    if(data.status === 'pass'){
                        $scope.populateLesson(data.lesson, data.tasks);
                    } else {
                        alert('LESSONS ERROR');
                    }
                })
                .error(function(){
                    alert('LESSONS ERROR');
                });
                
}]);