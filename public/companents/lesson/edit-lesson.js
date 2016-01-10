/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


teacherApp.controller('editLesson', ['$scope', '$http', '$upload', '$location', '$anchorScroll', '$stateParams',
    function ($scope, $http, $upload, $location, $anchorScroll, $route) {
        
        /* Init Params */
        //Lesson Subjects
        $scope.subjects = {
            type: "select",
            name: "subject",
            values: [ "הסטוריה", "תנ''ך", "העצמה אישית" ]
        },
        //Lesson Classes
        $scope.classes = {
            type: "select",
            name: "class",
            values: [ "ח' 2", "ט' 1", "יא' 1" ]
        },
        //Task Params to Add/Delete
        $scope.current          = -1,
        $scope.tasksLimit       = 4,
        $scope.tasksCounter     = 0,
        $scope.deletedIndexes   = [],
        //Lesson Form Params
        $scope.lessonForm       = {},
        $scope.lessonForm.tasks = [],
        $scope.lessonForm.draft = 0;
        $scope.errors           = {},
        $scope.res              = {},
        $scope.files            = [],
        //For Lang
        $scope._l               = _l,
        //Init Lesson Types
        $scope.lessonType=[
		{
			name: _l.DATA_NAME,
			description: _l.DATA_DESC,
			img: '/images/img.png',
			color: '#fc611f',
			type: 'data'
		},
		{
			name: _l.PIC_NAME,
			description: _l.PIC_DESC,
			img: '/images/text.png',
			color: '#fdc10b',
			type: 'pic'
		},
		{
			name: _l.GALLERY_NAME,
			description: _l.GALLERY_DESC,
			img: '/images/gallery.png',
			color: '#a6dae8',
			type: 'gallery'
		},
		{
			name: _l.PHOTO_NAME,
			description: _l.PHOTO_DESC,
			img: '/images/camera.png',
			color: '#14b0bf',
			type: 'photo'
		},
		{
			name: _l.PRESENTATION_NAME,
			description: _l.PRESENTATION_DESC,
			img: '/images/text.png',
			color: '#2e7980',
			type: 'presentation'
		},
		{
			name: _l.GAME_NAME,
			description: _l.GAME_DESC,
			img: '/images/star.png',
			color: '#cd6db3',
			type: 'game'
		},
		{
			name: _l.Q_A_NAME,
			description: _l.Q_A_DESC,
			img: '/images/text.png',
			color: '#8208C0',
			type: 'qa'
		},
		{
			name: _l.DIRECTOR_NAME,
			description: _l.DIRECTOR_DESC,
			img: '/images/LAMP_WHITE.png',
			color: '#23AE89',
			type: 'director'
		},
		{
			name: _l.QUESTIONNAIRE_NAME,
			description: _l.QUESTIONNAIRE_DESC,
			img: '/images/text.png',
			color: '#E94B3B',
			type: 'questionnaire'
		}
        
	],
        //Init Tasks Params
        $scope.tasks = [
                {
                        name:'1',
                        color:'#fff',
                        img:'',
                        guideShow:false,
                        guide:{},
                        type:'',
                        hideCustomLink:true,
                        sign:'+'
                },
                {
                        name:'2',
                        color:'#fff',
                        img:'',
                        guideShow:false,
                        guide:{},
                        type:'',
                        hideCustomLink:true,
                        sign:'+'
                },
                {
                        name:'3',
                        color:'#fff',
                        img:'',
                        guideShow:false,
                        guide:{},
                        type:'',
                        hideCustomLink:true,
                        sign:'+'
                },
                {
                        name:'4',
                        color:'#fff',
                        img:'',
                        guideShow:false,
                        guide:{},
                        type:'',
                        hideCustomLink:true,
                        sign:'+'
                }
	],      
        //Add New Task To Current Lesson
	$scope.addTask = function (task){
            if( $scope.tasksCounter < $scope.tasksLimit ){
                //Check If there a deleted tasks, to insert the new item instead of..
                if( $scope.deletedIndexes.length ) {
                    deletedIndex = $scope.deletedIndexes[0];
                    //delete first item
                    $scope.deletedIndexes.splice(0, 1);
                    
                    $scope.tasks[deletedIndex].name      = task.name;
                    $scope.tasks[deletedIndex].color     = task.color;
                    $scope.tasks[deletedIndex].img       = task.img;
                    $scope.tasks[deletedIndex].type      = task.type;
                    $scope.tasks[deletedIndex].guideShow = true;
                    $scope.tasksCounter++;
                } else {
                    if($scope.current > -1){
                        $scope.current++;
                        $scope.tasks[$scope.current].name      = task.name;
                        $scope.tasks[$scope.current].color     = task.color;
                        $scope.tasks[$scope.current].img       = task.img;
                        $scope.tasks[$scope.current].type      = task.type;
                        $scope.tasks[$scope.current].guideShow = true;
                        $scope.tasksCounter++;
                    } else {
                        $scope.tasks[0].name      = task.name;
                        $scope.tasks[0].color     = task.color;
                        $scope.tasks[0].img       = task.img;
                        $scope.tasks[0].type      = task.type;
                        $scope.tasks[0].guideShow = true;
                        $scope.current++;
                        $scope.tasksCounter++;
                    }
                }
            }
        },
        //Delete Selected Task
        $scope.deleteTask = function(index){
            if ( !confirm('האם ברצונך למחוק משימה זו?') ){
                return false;
            }
            $upload.upload({
                method: 'POST',
                url: '/teacher/deltask',
                data: {
                    'task_id'  : $scope.tasks[index].id
                }
            })
            .success(function(data, status, headers, config) {
                // Get lessonId And Complete insert tasks
                console.log('pass: ' + data);
            })
            .error(function(data){
                console.log('error: ' + data);
            });
            //Return all values to default
            $scope.tasks[index].id             = 0;
            $scope.tasks[index].text           = index + 1;
            $scope.tasks[index].name           = index + 1;
            $scope.tasks[index].color          = '#fff';
            $scope.tasks[index].img            = '';
            $scope.tasks[index].type           = '';
            $scope.tasks[index].customLink     = '';
            $scope.tasks[index].guideShow      = false;
            $scope.tasks[index].guide          = {};
            $scope.tasks[index].hideCustomLink = true;
            $scope.tasks[index].sign           = '+';
            
            var guide        = document.querySelector( "#guide" + index );
            guide.value      = '';
            var cet          = document.querySelector( "#cet" + index );
            cet.checked      = '';
            var google       = document.querySelector( "#google" + index );
            google.checked   = '';
            var customLink   = document.querySelector( "#custom-link" + index );
            customLink.value = '';
                
            $scope.tasksCounter--;
            $scope.deletedIndexes.push(index);
        },
        //Populate given task
        $scope.populateTask = function(tasks){
            for (var i = 0; i < Object.keys(tasks).length; i++) {
                $scope.tasks[i].id         = tasks[i].task_id;
                $scope.tasks[i].name       = tasks[i].name;
                $scope.tasks[i].color      = tasks[i].color;
                $scope.tasks[i].img        = tasks[i].img;
                $scope.tasks[i].type       = tasks[i].type;
                $scope.tasks[i].guide      = tasks[i].guied;
                $scope.tasks[i].customLink = tasks[i].custom_link;
                var guide                  = document.querySelector( "#guide" + i );
                guide.value                = tasks[i].guide;
                var cet                    = document.querySelector( "#cet" + i );
                cet.checked                = parseInt(tasks[i].cet);
                var google                 = document.querySelector( "#google" + i );
                google.checked             = parseInt(tasks[i].google);
                var customLink             = document.querySelector( "#custom-link" + i );
                customLink.value           = tasks[i].custom_link;
                $scope.tasks[i].guideShow  = true;
                
                $scope.current++;
                $scope.tasksCounter++;
            }
        },
        //Show the input of custom link/ file
        $scope.linkToggle = function(index){
            $scope.tasks[index].hideCustomLink = !$scope.tasks[index].hideCustomLink;
            if( $scope.tasks[index].sign === '+' ) {
                $scope.tasks[index].sign = '-';
            } else {
                $scope.tasks[index].sign = '+';
            }
        },
        //Store Selected Files
        $scope.onFileSelect = function($files, $index) {
            $scope.lessonForm.tasks[$index].customFile = $files;
            $scope.files[$index] = $files;
        },
        //Update Lesson
        $scope.submitLesson = function() {
            $upload.upload({
                method: 'POST',
                url: '/teacher/updatelesson',
                data: {
                    'lessonId'  : $scope.lessonForm.lessonId,
                    'lessonName': $scope.lessonForm.lessonName,
                    'lessonDesc': $scope.lessonForm.lessonDesc,
                    'class'     : $scope.lessonForm.class,
                    'subject'   : $scope.lessonForm.subject,
                    'deadLine'  : $scope.lessonForm.deadLine,
                    'draft'     : $scope.lessonForm.draft
                }
            })
            .success(function(data, status, headers, config) {
                // Get lessonId And Complete insert tasks
                $scope.uploadAndSubmit( data.lessonId );
            })
            .error(function(data){
                console.log('error: ' + data);
            }).then(function () {
                $location.hash('top');
                $anchorScroll();
                setTimeout( function () {
                    //$scope.lessonForm = {};
                    window.location = '/teacher';
                }, 3000);
            });
        },
        //Final Submit
        $scope.uploadAndSubmit = function($lessonId) {
            var lessonId = parseInt($lessonId);
            for (var i in $scope.lessonForm.tasks) {
                var task = $scope.lessonForm.tasks[i];
                    task.task_id = $scope.tasks[i].id ? $scope.tasks[i].id : 0;
                    task.type    = $scope.tasks[i].type;
                    task.name    = $scope.tasks[i].name;
                    task.color   = $scope.tasks[i].color;
                    task.img     = $scope.tasks[i].img;
                var file = $scope.files[i];
                $scope.upload = $upload.upload({
                    url: 'teacher/updatetask', 
                    method: 'POST',
                    headers: {'header-key': 'header-value'},
                    withCredentials: true,
                    data: { 'lesson_id' : lessonId,
                            'task'      : task },
                    file: file
                }).progress(function(evt) {
                        console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
                }).success(function(data, status, headers, config) {
                    // file is uploaded successfully
                    console.log(data.status + ': ' + data.msg);
                    $location.hash('top');
                    $anchorScroll();
                    $scope.res.isSuccessSubmit = true;
                    $scope.res.successSubmit   = _l.SUCCESS_LESSON_SUBMIT;
                })
                .error(function(data){
                    console.log('error: ' + data);
                    $location.hash('top');
                    $anchorScroll();
                    $scope.res.isErrorSubmit = true;
                    $scope.res.errorSubmit   = _l.FAILE_LESSON_SUBMIT;
                }).then(function () {
                    setTimeout( function () {
                        //$scope.lessonForm = {};
                        window.location = '/teacher';
                    }, 3000);
                });
            }
        },
        //Get Today Date!
        $scope.getTodayDate = function() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            
            var yyyy = today.getFullYear();
            if(dd < 10){
                dd = '0' + dd;
            }
            if(mm < 10){
                mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;
            return today;
        },
        //Input Date Checks
        $scope.isIllegalDate = function() {
            if( $scope.lessonForm.deadLine < $scope.getTodayDate() ) {
                alert(_l.SELECT_LEGAL_DATE);
            }
        },
        //Validation to Lesson Form Inputs
        $scope.checkInputs = function() {
            var valid                = true;
            $scope.errors.lessonName = 0;
            $scope.errors.class      = 0;
            $scope.errors.subject    = 0;
            $scope.errors.deadLine   = 0;
            $scope.errors.tasks      = 0;
            
            if( !$scope.lessonForm.lessonName ){
                $scope.errors.lessonName = 1;
                valid = false;
            }
            if( !$scope.lessonForm.class ){
                $scope.errors.class = 1;
                valid = false;
            }
            if( !$scope.lessonForm.subject ){
                $scope.errors.subject = 1;
                valid = false;
            }
            if( !$scope.lessonForm.deadLine ){
                $scope.errors.deadLine = 1;
                valid = false;
            } else {
                if( $scope.lessonForm.deadLine < $scope.getTodayDate() ) {
                    alert(_l.SELECT_LEGAL_DATE);
                    valid = false;
                }
            }
            for( var i = 0; i < $scope.tasks.length; i++ ){
                if( $scope.tasks[i].guideShow ){
                    if( !document.querySelector( "#guide" + i ).value ){
                        $scope.errors.tasks = 1;
                        valid = false;
                    }
                }
            }
            
            if( !valid ) return false;
            return true;
        },
        //Save Lesson As Draft
        $scope.saveAsDraft = function() {
            $scope.lessonForm.draft = 1;
        };
        //Check Inputs Then Submit
        $scope.preSubmit = function() {
            //Upload Files And Submit Handling
            if( $scope.checkInputs() ) {
                $scope.submitLesson();
            }
        };
        
        //Populate Lesson Details
        if( isNaN($route.lessonId) ){
            alert('Error');
        } else {
            $http.get('teacher/getlesson', {
                params: {
                    lesson_id : $route.lessonId
                }})
                    .success(function(data){
                        if(data.status === 'pass'){
                            //Populate Lesson Info
                            $scope.lessonForm.lessonId   = $route.lessonId;
                            $scope.lessonForm.lessonName = data.lesson.name;
                            $scope.lessonForm.lessonDesc = data.lesson.description;
                            $scope.lessonForm.class      = data.lesson.class;
                            $scope.lessonForm.subject    = data.lesson.subject;
                            $scope.lessonForm.deadLine   = data.lesson.dead_line;
                            //Populate Tasks Of Lesson
                            $scope.populateTask(data.tasks);
                        } else {
                            alert('LESSONS ERROR');
                        }
                    })
                    .error(function(){
                        alert('LESSONS ERROR');
                    });
        }
}]);