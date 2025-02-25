<div class="container-fluid">
    <div class="row">
        <div class="side-menu-fixed">
            <div class="scrollbar side-menu-bg" style="overflow: scroll">
                <ul class="nav navbar-nav side-menu" id="sidebarnav">
        
                    <li>
    <a href="{{ url('/dashboard') }}">
        <div class="pull-left"><i class="ti-home"></i><span class="right-nav-text">Home Page</span>
        </div>
        <div class="clearfix"></div>
    </a>
</li>

                    <!-- Years & Specializations-->
                  
                     <!-- FavoriteServices-->
                     <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Years&Specializations">
                            <div class="pull-left"><i class="ti-calendar"></i><span
                                    class="right-nav-text">Years & Specializations</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Years&Specializations" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAllServicesYearsAndSpecializations')}}">listYears&Specializations</a></li>

                        </ul>
                    </li>
                     <!-- Roles-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Roles">
                            <div class="pull-left"><i class="ti-pencil-alt"></i><span
                                    class="right-nav-text">Roles</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Roles" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAllRoles')}}">listRoles</a></li>

                        </ul>
                    </li>
                     <!-- Advanced users-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Advanced users">
                            <div class="pull-left"><i class="ti-user"></i><span
                                    class="right-nav-text">AdvancedUsers</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Advanced users" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAllAdvancedUsers')}}">list Advanced userss</a></li>

                        </ul>
                    </li>
                     <!-- Normal users-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Normal users">
                            <div class="pull-left"><i class="ti-user"></i><span
                                    class="right-nav-text">NormalUsers</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Normal users" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAllNormalUsers')}}">listNormalusers</a></li>

                        </ul>
                    </li>
                     <!-- PublicServices-->
                     <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Publicservices">
                            <div class="pull-left"><i class="ti-support"></i><span
                                    class="right-nav-text">PublicServices</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Publicservices" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAllParentServices')}}">listPublicServices</a></li>
                            
                        </ul>
                    </li>
                     <!-- FavoriteServices-->
                     <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Favoriteservices">
                            <div class="pull-left"><i class="ti-heart"></i><span
                                    class="right-nav-text">FavoriteServices</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a> 
                        <ul id="Favoriteservices" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAllParentInterestedServices')}}">listFavoriteservices</a></li>

                        </ul>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#myservices">
                            <div class="pull-left"><i class="ti-comments"></i><span
                                    class="right-nav-text">myservices</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a> 
                        <ul id="myservices" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showMyAllParentFromServiceManager')}}">listmyservices</a></li>

                        </ul>
                    </li>
                     <!-- Advertisements-->
                     <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Advertisements">
                            <div class="pull-left"><i class="ti-announcement"></i><span
                                    class="right-nav-text">Advertisements</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Advertisements" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAnnouncements')}}">listAdvertisements</a></li>

                        </ul>
                    </li>
                    
                     <!-- Saved advertisements-->
                     <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Savedadvertisements">
                            <div class="pull-left"><i class="ti-bookmark"></i><span
                                    class="right-nav-text">Savedadvertisements</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Savedadvertisements" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showAllAnnouncements')}}">listSavedAdvertisements</a></li>

                        </ul>
                    </li>
                    
                     <!-- Reports & Statistics-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#showprofile">
                            <div class="pull-left"><i class="ti-comment-alt"></i><span
                                    class="right-nav-text">showprofile</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="showprofile" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{route('showServiceManagerProfile')}}">showprofile</a></li>

                        </ul>
                    </li>

                        <!-- Reports & Statistics-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Statistics">
                            <div class="pull-left"><i class="ti-bar-chart"></i><span
                                    class="right-nav-text">Statistics</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Statistics" class="collapse" data-parent="#sidebarnav">
                            <li><a href="/g">Statistics</a></li>

                        </ul>
                    </li>
                    
                    

                  
                </ul>
            </div>
        </div>

        