<?php 
	ob_start();
	session_start();
	$pageTitle = 'FAQ';
	include 'init.php';
?>


  <!-- Start FAQ Intro -->
        
        <section class="faq text-center">
            <div class="container">
                <h1>Frequently Asked Questions</h1>
                <hr>
                <p class="lead">Here You Will Find All Questions You Are Searching For And The Full Knowledgebase</p>
            </div>
        </section>
        
        <!-- End FAQ Intro -->
        
        <!-- Start FAQ Accordion -->
        
        <section class="faq-questions">
            <div class="container">
                <div class="panel-group" id="accordion" roles="tablist" aria-multiselectable="true">
                    
                    <!-- Start Question 1 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-one">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-one" aria-expanded="true" arial-controls="collapse-one">
                                    #1 How To Use My Website?
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-one" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-one">
                            <div class="panel-body">
                                This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question 
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 1 -->
                    
                    <!-- Start Question 2 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-two">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-two" aria-expanded="true" arial-controls="collapse-two">
                                    #2 How To Buy Product?
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-two" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-two">
                            <div class="panel-body">
                                This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question 
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 2 -->
                    
                    <!-- Start Question 3 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-three">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-three" aria-expanded="true" arial-controls="collapse-three">
                                    #3 How To Create Add For New Product?
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-three" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-three">
                            <div class="panel-body">
                                This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question 
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 3 -->
                    
                    <!-- Start Question 4 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-four">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-four" aria-expanded="true" arial-controls="collapse-four">
                                    #4 How To Create New Account?
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-four" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-four">
                            <div class="panel-body">
                                This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question 
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 4 -->
                    <!-- Start Question 4 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-four">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-four" aria-expanded="true" arial-controls="collapse-four">
                                    #4 How To Contact With Managers?
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-four" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-four">
                            <div class="panel-body">
                                This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question This Is Test Message Describe The Answer Of The First Question 
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 4 -->
                    
                </div>
            </div>
        </section>
        
        <!-- End FAQ Accordion -->

<?php 
	include $tpl . 'Footer.php';
	ob_end_flush();
?>