(function($){
    $.fn.Video = function(options, callback)
    {
        return(new Video(this, options));
    };
var idleEvents = "mousemove keydown DOMMouseScroll mousewheel mousedown reset.idle";

var defaults = {
	instanceName:"player1",
	instanceTheme:"dark",
    autohideControls:3,                        
	hideControlsOnMouseOut:"No",
	playerLayout: "fixedSize", 	
	videoPlayerWidth:1006,                       
	videoPlayerHeight:420, 
	lightBox:true,                               
	lightBoxAutoplay: false,                    
	lightBoxThumbnail:"images/preview_images/poster.jpg",
	lightBoxThumbnailWidth: 400,                
	lightBoxThumbnailHeight: 220,                
	lightBoxCloseOnOutsideClick: true,  	             
	playlist:"Right playlist",                  
	playlistScrollType:"light",                 
	playlistBehaviourOnPageload:"opened (default)",		 
	autoplay:false,                              
	colorAccent:"#cc181e",                    
	vimeoColor:"00adef",                         
	youtubeControls:"custom controls",			
	youtubeSkin:"light",                         
	youtubeColor:"white",                     
	youtubeShowRelatedVideos:"No",				
	videoPlayerShadow:"effect1",                 
	loadRandomVideoOnStart:"No",                
	shuffle:"No",				                 
	posterImg:"images/preview_images/poster.jpg",
	onFinish:"Play next video",                  
	nowPlayingText:"Yes",                        
	fullscreen:"Fullscreen native",              
	rightClickMenu:true,                         
	hideVideoSource:true,						
	showAllControls:true,						
	allowSkipAd:true,                           
	infoShow:"Yes",                              
	shareShow:"Yes",                             
	facebookShow:"Yes",                          
	twitterShow:"Yes",                          
	mailShow:"Yes",                              
	facebookShareName:"",      
	facebookShareLink:"",  
	facebookShareDescription:"", 
	facebookSharePicture:"", 
	twitterText:"",			 
	twitterLink:"", 
	twitterHashtags:"",		 
	twitterVia:"",				
	googlePlus:"",
	logoShow:"Yes",                              
	logoClickable:"Yes",                         
	logoPath:"",             
	logoGoToLink:"",       
	logoPosition:"bottom-left",                 
	embedShow:"Yes",                            
	embedCodeSrc:"", 
	embedCodeW:"746",                            
	embedCodeH:"420",                           
	embedShareLink:"",
	youtubePlaylistID:"",                        
	youtubeChannelID:"",                       
	videos:[
		{
			videoType:"",
			title:"",
			youtubeID:"",
			vimeoID:"",
			mp4:"",
			imageUrl:"",
			imageTimer:4,
			prerollAD:"no",
			prerollGotoLink:"",
			preroll_mp4:"",
			prerollSkipTimer:5,
			midrollAD:"no",                                                                  
			midrollAD_displayTime:"",                                                    
			midrollGotoLink:"",                                         
			midroll_mp4:"", 
			midrollSkipTimer:5,	
			postrollAD:"no",                                                                
			postrollGotoLink:"",                                        
			postroll_mp4:"",  
			postrollSkipTimer:5,
			popupImg:"",                        			  
			popupAdShow:"no",                                                                
			popupAdStartTime:"",                                                         
			popupAdEndTime:"",                                                          
			popupAdGoToLink:"",
			description:"",
			thumbImg:"",
			info:""
		}
	]
};

/*var isAndroid = (/android/gi).test(navigator.appVersion),
    isIDevice = (/iphone|ipad/gi).test(navigator.appVersion),
    isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),
    hasTouch = 'ontouchstart' in window && !isTouchPad,
    RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize',
    CLICK_EV = hasTouch ? 'touchend' : 'click',
    START_EV = hasTouch ? 'touchstart' : 'mousedown',
    MOVE_EV = hasTouch ? 'touchmove' : 'mousemove',
    END_EV = hasTouch ? 'touchend' : 'mouseup';
*/
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() /*|| isMobile.iOS()*/ || isMobile.Opera() || isMobile.Windows());
    }
};

var CLICK_EV = isMobile.any() ? 'touchend' : 'click';
var START_EV = isMobile.any() ? 'touchstart' : 'mousedown';
var MOVE_EV = isMobile.any() ? 'touchmove' : 'mousemove';
var END_EV = isMobile.any() ? 'touchend' : 'mouseup';
var RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize'

var Video = function(parent, options)
{
    var self=this;
	this._class  = Video;
	this.parent  = parent;
	this.parentWidth = this.parent.width();
	this.parentHeight = this.parent.height();
	this.windowWidth = $(window).width();
	this.windowHeight = $(window).height();
	this.options = $.extend({}, defaults, options);
	this.sources = this.options.srcs || this.options.sources;
	this.state = null;
	this.inFullScreen = false;
	this.realFullscreenActive=false;
	this.stretching = false;
	this.infoOn = false;
	this.lightBoxOn = false;
	this.adOn = false;
	this.skipCountOn = false;
	this.skipBoxOn = false;
	this.shareOn = false;
	this.videoPlayingAD = false;
	this.embedOn = false;
	pw = false;
	this.loaded       = false;
	this.readyList    = [];
    this.videoAdStarted=false;
    this.youtubeReady=false;
	this.ADTriggered=false;
	this.volPerc=1;
	this.html5STARTED=false;
	this.YTAPIReady=false;
	this.isYoutubeAPICreated = false;
	this.ytSkin = this.options.youtubeSkin;
    this.ytColor = this.options.youtubeColor;
	this.ytSkin.toString();
    this.ytColor.toString();
	this.youtubeControls = this.options.youtubeControls;
	this.midrollPlayed = false;
	this.postrollPlayed = false;
	this.prerollActive = true;
	this.midrollActive = false;
	this.postrollActive = false;
	this.qualityBtnEnabled=false;
	this.lightBoxThumbnail;
	this.lightBoxOverlay;
	this.lightBoxInitiated = false;
	this.globalPrerollAds_arr = self.options.globalPrerollAds.split(';');
	this.poster2Showing = false;
	switch(this.options.youtubeShowRelatedVideos){
		case "Yes":
			self.ytShowRelatedVideos = 1;
		break;
		case "No":
			self.ytShowRelatedVideos = 0;
		break;
	}
	this.isMobile = isMobile;

    // this.hasTouch = hasTouch;
    this.RESIZE_EV = RESIZE_EV;
    this.CLICK_EV = CLICK_EV;
    this.START_EV = START_EV;
    this.MOVE_EV = MOVE_EV;
    this.END_EV = END_EV;

    this.canPlay = false;
    this.myVideo = document.createElement('video');
    self.deviceAgent = navigator.userAgent.toLowerCase();
    self.agentID = self.deviceAgent.match(/(iphone|ipod)/);

	//////////////////////
	////jQuery version////
	//////////////////////
		if(this.options.playerLayout == "fitToBrowser" || options.playerLayout == "fitToBrowser"){
			var videoplayers = $("#vpl");
			$.each(videoplayers, function(){
				var fixedCont = $("<div />")
				.addClass("fixedCont")
				.css({
						position: 'fixed',
						width: '100%',
						height: '100%',
						top: 0,
						left: 0,
						background: '#000000',
						zIndex: 2147483647
					});
				videoplayers.parent().append(fixedCont);
				videoplayers.appendTo(fixedCont);
			})
		}

	this.setupElement();
    this.setupElementAD();
	
		
	if(!this.options.rightClickMenu){
		$("#vpl").bind('contextmenu',function() { return false; });
		$(".vpl").bind('contextmenu',function() { return false; });
		
		if(this.options.lightBox)
			$(".vpl-mainContainer").bind('contextmenu',function() { return false; });
	}

	$(options.videos).each(function ()
    {
		if(this.videoType == "youtube")
			self.includeYoutubeAPI = true;
	});

	if(!this.includeYoutubeAPI)
	{
		this.init();
	}
	else //youtube single / channel / playlist
	{
		var tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag); // Include the API inside the page.
	
		if(this.youtubeControls == "default controls"){
			this.options.posterImg=="";
			this.element.css("visibility","hidden");
		}
		
		if(this.options.videoType!="YouTube playlist" && this.options.videoType!=undefined){
			this.options.youtubePlaylistID="";
		}
		if(this.options.videoType!="YouTube channel" && this.options.videoType!=undefined){
			this.options.youtubeChannelID="";
		}
		
		if( (this.options.youtubePlaylistID != "" || this.options.youtubeChannelID != "") /*|| (this.options.videoType=="YouTube playlist")*/){
		
			var youtubePlaylistID = this.options.youtubePlaylistID;
			var youtubeChannelID = this.options.youtubeChannelID;
			this.url;
			
			//===YOUTUBE CHANNEL====//
			//https://www.youtube.com/channel/USERNAME
			//var channelURL = 'http://gdata.youtube.com/feeds/api/users/'+youtubeChannelID+'/uploads?alt=json&orderby=published';
			var channelURL = 'https://www.googleapis.com/youtube/v3/search?order=date&maxResults=50&part=snippet&channelId='+youtubeChannelID+'&key=AIzaSyClbVoeyPLBHb9n6Abm0z-AlrzNKeWLKTc';
			
			//===YOUTUBE PLAYLIST====//
			// var playListURL = 'http://gdata.youtube.com/feeds/api/playlists/'+youtubePlaylistID+'?v=2&alt=json';
			var playListURL = 'https://www.googleapis.com/youtube/v3/playlistItems?&maxResults=50&part=snippet&playlistId='+youtubePlaylistID+'&key=AIzaSyClbVoeyPLBHb9n6Abm0z-AlrzNKeWLKTc';

			var videoURL= 'http://www.youtube.com/watch?v=';

			if(youtubePlaylistID != "")
				this.url = playListURL;
			else if(youtubeChannelID != "")
				this.url = channelURL;
			
			this.id=-1;
			this.youtube_array = new Array();
			this.ads_array = new Array();
			this.data;

			//if jquery
			$(this.options.videos).each(function loopingItems()
			{
				var obj=
				{
					prerollAD:this.prerollAD,
					prerollGotoLink:this.prerollGotoLink,
					preroll_mp4:this.preroll_mp4,
					prerollSkipTimer:this.prerollSkipTimer,
					midrollAD:this.midrollAD,
					midrollAD_displayTime:this.midrollAD_displayTime,
					midrollGotoLink:this.midrollGotoLink,
					midroll_mp4:this.midroll_mp4,
					midrollSkipTimer:this.midrollSkipTimer,
					postrollAD:this.postrollAD,
					postrollGotoLink:this.postrollGotoLink,
					postroll_mp4:this.postroll_mp4,
					postrollSkipTimer:this.postrollSkipTimer,
					popupAdShow:this.popupAdShow,
					popupImg:this.popupImg,
					popupAdStartTime:this.popupAdStartTime,
					popupAdEndTime:this.popupAdEndTime,
					popupAdGoToLink:this.popupAdGoToLink
				};
				self.ads_array.push(obj);
			});				
			
			this.requestYTList();
		}
		else{
			this.init();
			
			this.waitAPIReady();
			/*this.setupYTAPI_timeout = setTimeout(function() {
				self.setupYoutubeAPI();
			},500);*/
		}
	}
};
Video.fn = Video.prototype;

Video.fn.waitAPIReady = function()
{
	var self = this;
	var APIIsLoaded = false;
	if (!this.YTAPIReady)
	{
		if (typeof(YT) != 'undefined' && typeof(YT.Player) != 'undefined')
		{
			this.YTAPIReady = true;
			
			if (this.isYoutubeAPICreated)
			{
				this.createYoutubeInstance();
			}
			else
			{
				/*clearTimeout(self.setupYTAPI_timeout);*/
				this.setupYoutubeAPI();
			}
		}
		else
		{
			/*setTimeout(function() {
				self.waitAPIReady();
			},400);*/
			var apiReadyInterval = setInterval(function() {
				if (typeof(YT.Player) == 'function' && !APIIsLoaded){
					APIIsLoaded = true;
					clearInterval(apiReadyInterval);
					self.waitAPIReady();
				}
			}, 400);
			
		}
	}
};

Video.fn.setupYoutubeAPI = function()
{
	var self = this;
	
	if(this.isYoutubeAPICreated) return;
		this.isYoutubeAPICreated = true;
		
	if (this.YTAPIReady)
	{
		this.createYoutubeInstance();
	}
	else
	{
		if (!window.onYouTubeIframeAPIReady)
		{
			window.onYouTubePlayerAPIReady = function(){
				self.YTAPIReady=true;
				self.createYoutubeInstance();
			}
		}
	}
}

Video.fn.createYoutubeInstance = function(){

	var self = this;

	if(this.options.youtubeControls == "custom controls")
	{
		this.youtubePlayer = new YT.Player(this.options.instanceName+'youtube', {
			height: '100%',
			width: '100%',
			events: {
				'onReady': this._playlist.onPlayerReady,
				'onStateChange': this._playlist.onPlayerStateChange,
				'onPlaybackQualityChange': this.onPlayerPlaybackQualityChange
			},
			playerVars:
			{
				// theme:this.ytSkin, //light,dark
				// color:this.ytColor, //red, white
				rel:this.ytShowRelatedVideos,
				wmode:'transparent',
				
				controls:0,     //1 default controls, 0 hide controls
				enablejsapi:1, //1 enables the player to be controlled via IFrame or JavaScript Player API calls
				iv_load_policy : 3, //1 show annotations, 3 hide annotations
				//modestbranding: 1,//1 prevent youtube logo in controlbar, 0 youtube logo displays
				showinfo:0
				// autohide:1
			}
		});
	}
	else if(this.options.youtubeControls == "default controls")
	{			
		this.youtubePlayer = new YT.Player(this.options.instanceName+'youtube', {
			height: '100%',
			width: '100%',
			events: {
				'onReady': this._playlist.onPlayerReady,
				'onStateChange': this._playlist.onPlayerStateChange,
				'onPlaybackQualityChange': this.onPlayerPlaybackQualityChange
			},
			playerVars:
			{
				theme:this.ytSkin, //light,dark
				color:this.ytColor, //red, white
				rel:this.ytShowRelatedVideos,
				wmode:'transparent',
				
				controls:1,     //1 default controls, 0 hide controls
				enablejsapi:1, //1 enables the player to be controlled via IFrame or JavaScript Player API calls
				iv_load_policy : 3, //1 show annotations, 3 hide annotations
				modestbranding: 0,//1 prevent youtube logo in controlbar, 0 youtube logo displays
				showinfo:1,
				autohide:1
			}
		});
	}
}

Video.fn.requestYTList = function(){
	var self = this;

	if (self.nextPageToken!=undefined)
		url  = this.url + "&pageToken=" + self.nextPageToken
	else
		url = this.url

	$.ajax({
		url: url,
		success: function(data) {
			self.data = data;
			self.nextPageToken = data.nextPageToken;
			
			$.each(data.items, function(i, item) {
			
				self.id=self.id+1;
				var feedTitle = item.snippet.title;
				var feedInfo = item.snippet.description;
				var authorName = item.snippet.channelTitle;
				if(self.options.youtubePlaylistID!="")
					var videoID = item.snippet.resourceId.videoId;
				if(self.options.youtubeChannelID!="")
					var videoID = item.id;
				var feedURL = 'https://www.youtube.com/watch?v='+videoID;
				
				var thumb;
				if(item.snippet.thumbnails!=undefined)
				thumb = item.snippet.thumbnails.default.url;
				else
				thumb="";
				
				var _o=
				{
					prerollAD:"no",
					prerollGotoLink:"prerollGotoLink",
					preroll_mp4:"preroll_mp4",
					prerollSkipTimer:"prerollSkipTimer",
					midrollAD:"no",
					midrollAD_displayTime:"midrollAD_displayTime",
					midrollGotoLink:"midrollGotoLink",
					midroll_mp4:"midroll_mp4",
					midrollSkipTimer:"midrollSkipTimer",
					postrollAD:"no",
					postrollGotoLink:"postrollGotoLink",
					postroll_mp4:"postroll_mp4",
					postrollSkipTimer:"postrollSkipTimer",
					popupAdShow:"no",
					popupImg:"popupImg",
					popupAdStartTime:"popupAdStartTime",
					popupAdEndTime:"popupAdEndTime",
					popupAdGoToLink:"popupAdGoToLink"
				};
				self.ads_array.push(_o);
				
				var obj=
				{
					id: self.id,
					title:feedTitle,
					videoType:"youtube",
					youtubeID:videoID,
					vimeoID:this.vimeoID,
					video_path_mp4:this.mp4,
					enable_mp4_download:this.enable_mp4_download,
					prerollAD:self.ads_array[self.id].prerollAD,
					prerollGotoLink:self.ads_array[self.id].prerollGotoLink,
					preroll_mp4:self.ads_array[self.id].preroll_mp4,
					prerollSkipTimer:self.ads_array[self.id].prerollSkipTimer,
					midrollAD:self.ads_array[self.id].midrollAD,
					midrollAD_displayTime:self.ads_array[self.id].midrollAD_displayTime,
					midrollGotoLink:self.ads_array[self.id].midrollGotoLink,
					midroll_mp4:self.ads_array[self.id].midroll_mp4,
					midrollSkipTimer:self.ads_array[self.id].midrollSkipTimer,
					postrollAD:self.ads_array[self.id].postrollAD,
					postrollGotoLink:self.ads_array[self.id].postrollGotoLink,
					postroll_mp4:self.ads_array[self.id].postroll_mp4,
					postrollSkipTimer:self.ads_array[self.id].postrollSkipTimer,
					popupAdShow:self.ads_array[self.id].popupAdShow,
					popupImg:self.ads_array[self.id].popupImg,
					popupAdStartTime:self.ads_array[self.id].popupAdStartTime,
					popupAdEndTime:self.ads_array[self.id].popupAdEndTime,
					popupAdGoToLink:self.ads_array[self.id].popupAdGoToLink,
					description:authorName,
					thumbImg:thumb,
					info: feedInfo
				};
				self.youtube_array.push(obj);
			});
			
			if(data.nextPageToken!=undefined){
				self.requestYTList();
			}
			else{
				self.init();
				self.waitAPIReady();
			}
		}
	});
}
Video.fn.init = function init()
{
	// Determine the mobile operating system.
	// This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
	function getMobileOperatingSystem() {
		var userAgent = navigator.userAgent || navigator.vendor || window.opera;
		// Windows Phone must come first because its UA also contains "Android"
		if (/windows phone/i.test(userAgent)) {
			return true;
		}
		if (/android/i.test(userAgent)) {
			return true;
		}
		// iOS detection
		if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
			return true;
		}
		return false;
	}
	var vplIsMobile = getMobileOperatingSystem();

    var self=this;
	
                self.preloader = $("<div />");
                self.preloader.addClass("vpl-preloader");
                self.element.append(self.preloader);
				
				self.preloaderAD = $("<div />");
                self.preloaderAD.addClass("vpl-preloader");
                self.elementAD.append(self.preloaderAD);

                this.videoElement = $("<video />");
                this.videoElement.addClass("vpl-video-player");
                this.videoElement.attr({
                    width:this.options.width,
                    height:this.options.height,
                    // autoplay:this.options.autoplay,
                    preload:this.options.preloadSelfHosted,
					// controls: this.options.controls
					controls: vplIsMobile
                });
				
                this.videoElementAD = $("<video />");
                this.videoElementAD.addClass("vpl-ads");
                this.videoElementAD.attr({
                    width:this.options.width,
                    height:this.options.height,
                    // autoplay:this.options.autoplay,
                    preload:this.options.preloadSelfHosted,
                    controls:this.options.controls
                });
				
				if(isMobile.iOS()){
					//enable iOS 10+ video inline
					this.videoElement.attr('playsinline','').attr('webkit-playsinline','');
					this.videoElementAD.attr('playsinline','').attr('webkit-playsinline','');
					//enable iOS 10+ autoplay
					if(this.options.autoplay){
						this.videoElement.attr('muted','')
						this.videoElement.muted = true;
						this.videoElement.attr('autoplay','autoplay')
					}
				}
				
				this.controls = $("<div />");
				this.controls.addClass("vpl-controls");
				this.controls.addClass("vpl-disabled");
				if(this.element)
					this.element.append(this.controls);
				if(!this.options.showAllControls)
					this.controls.hide();
				
				this.nowPlayingTitle = $("<div />")
				.addClass("vpl-title");

				this.controls.addClass("vpl-bg"+" "+this.options.instanceTheme);
				this.nowPlayingTitle.addClass("vpl-bg"+" "+this.options.instanceTheme);

				if(!this.options.showAllControls)
					this.nowPlayingTitle.hide();
			    if(this.element)
				this.element.append(this.nowPlayingTitle);
				
				this.setupButtonsOnScreen();

                self._playlist = new PLAYER.Playlist($, self, self.options, self.mainContainer, self.element, self.preloader, self.preloaderAD, self.myVideo, this.canPlay, self.CLICK_EV, pw, self.deviceAgent, self.agentID, self.youtube_array, self.isMobile);

                if(self.options.playlist=="Right playlist")
                {
                    self.playerWidth = self.options.videoPlayerWidth - self._playlist.playlistW;
                    self.playerHeight = self.options.videoPlayerHeight;
                }
                else if(self.options.playlist=="Bottom playlist")
                {
                    self.playerWidth = self.options.videoPlayerWidth;
                    self.playerHeight = self.options.videoPlayerHeight - self._playlist.playlistH;
                }
                else if(self.options.playlist=="Off")
                {
                    self.playerWidth = self.options.videoPlayerWidth;
                    self.playerHeight = self.options.videoPlayerHeight;
                }

                self.playlistWidth = self._playlist.playlistW;

                self.initPlayer();
                self.resize();
                self.resizeAll();
				self.autohideControls();
};

Video.fn.initPlayer = function()
{
	var self = this;
    this.setupHTML5Video();
    this.setupHTML5VideoAD();

	this.setupEvents();
	this.change("initial");
	this.setupControls();
	this.load();
	this.setupAutoplay();
	this.setupLightBox();
	this.setupElements();
	
	this.element.bind("idle", $.proxy(this.idle, this));
	this.element.bind("state.videoPlayer", $.proxy(function(){
		this.element.trigger("reset.idle");
	}, this))

    this.secondsFormat = function(sec)
    {
        if(isNaN(sec))
        {
            sec=0;
        }
        var result  = [];

        var minutes = Math.floor( sec / 60 );
		if(minutes>60)
			minutes = minutes%60
        var hours   = Math.floor( sec / 3600 );
        var seconds = (sec == 0) ? 0 : (sec % 60)
        seconds     = Math.round(seconds);

        //to calclate tooltip time
        var pad = function(num) {
            if (num < 10)
                return "0" + num;
            return num;
        }

        if (hours > 0)
            result.push(pad(hours));

        result.push(pad(minutes));
        result.push(pad(seconds));

        return result.join(":");
    };

    var self = this;

    $(window).resize(function() {

        if(!self.inFullScreen && !self.realFullscreenActive)
        {
            self.resizeAll();
        }
		if(self.inFullScreen){
			self.fullScreen(self.inFullScreen)
		}
    });

	//resize on browser click
	$(window).bind(this.RESIZE_EV,function(e)
    {
		if(!self.inFullScreen && !self.realFullscreenActive)
        self.resizeAll();
    });
	
    $(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange',function(e)
    {
        //detecting real fullscreen change
        self.resize(e);
    });

    this.resize = function(e)
    {
        if(document.webkitIsFullScreen || document.fullscreenElement || document.mozFullScreen)
        {
            this._playlist.hidePlaylist();
            this.element.addClass("vpl-fullScreen");
            this.elementAD.addClass("vpl-fullScreen");
            $(this.mainContainer).find(".fa-expand").removeClass("fa-expand").addClass("fa-compress");
            $(this.fsEnterADBox).find(".fa-expandAD").removeClass("fa-expandAD").addClass("fa-compressAD");
            self.element.width("100%");
            self.element.height("100%");
            self.elementAD.width("100%");
            self.elementAD.height("100%");
			self.mainContainer.width("100%");
            self.mainContainer.height("100%");
            self.mainContainer.css("position","fixed");
            self.mainContainer.css("left",0);
            self.mainContainer.css("top",0);
			//for multiple instances after THREEx.request
			//==self.mainContainer.parent().css("zIndex",999999);==//
			self.realFullscreenActive=true;
			
			this.timeElapsed.show();
			this.timeTotal.show();
			this.volumeTrack.show();
			this.rewindBtnWrapper.show();
			this.unmuteBtnWrapper.show();
			this.videoTrack.show();
			this.resizeVideoTrack();
        }
        else
        {
            this._playlist.showPlaylist();
            this.element.removeClass("vpl-fullScreen");
            this.elementAD.removeClass("vpl-fullScreen");
            $(this.mainContainer).find(".fa-compress").removeClass("fa-compress").addClass("fa-expand");
            $(this.fsEnterADBox). find(".fa-compressAD").removeClass("fa-compressAD").addClass("fa-expandAD");
            self.element.width(self.playerWidth);
            self.element.height(self.playerHeight);

            self.elementAD.width(self.playerWidth);
            self.elementAD.height(self.playerHeight);
			
			self.mainContainer.css("left","");
            self.mainContainer.css("top","");
			if(self.options.playerLayout == "fitToContainer"  || self.options.playerLayout == "fitToBrowser")
			{
				self.mainContainer.width("100%");
				self.mainContainer.height("100%");
			}
			else if (self.options.playerLayout == "fixedSize"){
				self.mainContainer.width(self.options.videoPlayerWidth);
				self.mainContainer.height(self.options.videoPlayerHeight);
			}
			
			
			self.mainContainer.css("position","absolute");
			
            if(this.stretching)
            {
                //back to stretched player
                this.stretching=false;
                this.toggleStretch();
            }

            self.element.css({zIndex:455558 });

            if(self._playlist.videos_array[self._playlist.videoid].prerollAD=="yes" || self.options.showGlobalPrerollAds){
                if(!self._playlist.videoAdPlayed && self.videoAdStarted){
                    self.elementAD.css({
                        zIndex:455559
                    });
                }
                else{
                        self.elementAD.css({
                            zIndex:455557
                        });
                }
            }
			self.mainContainer.parent().css("zIndex",1);
			self.mainContainer.css("zIndex",999999);
			self.realFullscreenActive=false;
            self.resizeAll();
        }
		this.resizeVideoTrack();
		this.positionOverScreenButtons();
		this.positionLogo();
		this.positionPopup();
		// this.positionPoster();
		this.resizeBars();
		if(self.options.hideControlsOnMouseOut=="Yes")
			this.hideControls();
		
    }
};
Video.fn.setupLightBox = function(){
	var self = this;
	///////////////////////
	//// lightbox mode ////
	///////////////////////
	if(this.options.lightBox){
		this.options.playerLayout = "fixedSize"
		var videoplayers = this.mainContainer.parent();
		
		$.each(videoplayers, function(){
			self.lightBoxOverlay = $("<div />")
				.addClass("vpl-lightBoxOverlay")
				.hide()
				.css({
					opacity: 0
				})

				self.lightBoxCloseBtnWrapper = $("<div />")
					.addClass("vpl-lightBoxCloseBtnWrapper")
					.addClass("vpl-bg"+" "+self.options.instanceTheme)
					.addClass("vpl-playerElement")
					.bind(self.CLICK_EV, function(){
						self.toggleLightBox();
					});
				self.mainContainer.append(self.lightBoxCloseBtnWrapper)
				self.lightBoxCloseBtn = $("<span />")
					.attr("aria-hidden","true")
					.attr("id", "vpl-lightBoxCloseBtn")
					.addClass("fa")
					.addClass("icon-general")
					.addClass("vpl-controlsColor"+" "+self.options.instanceTheme)
					.addClass("fa-times")		
				self.lightBoxCloseBtnWrapper.append(self.lightBoxCloseBtn);
				
				self.lightBoxOverlayTransparent = $("<div />")
					.addClass("vpl-lightBoxOverlayTransparent")
					.bind(self.CLICK_EV, function(){
						if(self.options.lightBoxCloseOnOutsideClick)
						self.toggleLightBox();
					})
					.appendTo(self.lightBoxOverlay);
		
			
			self.mainContainer.addClass("vpl-lightBoxBorder");
				
			videoplayers.parent().append(self.lightBoxOverlay);
			self.mainContainer.appendTo(self.lightBoxOverlay);
			
			
			self.lightBoxThumbnailWrap = $("<div />")
				.addClass("vpl-lightBoxThumbnailWrap")
				.bind(self.CLICK_EV, function(){
					self.toggleLightBox();
				})
				.css({
					cursor: 'pointer',
					width: self.options.lightBoxThumbnailWidth,
					height: self.options.lightBoxThumbnailHeight
				})
				.appendTo(videoplayers)
			
				self.lightBoxThumbnail = $('<img class="vpl-lightBoxThumbnail">')
					.attr('src', self.options.lightBoxThumbnail)
					.appendTo(self.lightBoxThumbnailWrap)
					
				
				self.lightBoxPlayButton = $("<div />");
				self.lightBoxPlayButton.addClass("vpl-start-button")
					.attr("aria-hidden","true")
					.addClass("fa")
					.addClass("fa-play"+" "+self.options.instanceTheme)
					.appendTo(self.lightBoxThumbnailWrap)
		})
	}
}
Video.fn.setColorAccent = function(colorAccent, btn){
	var self=this;
	$('.vpl-themeColor').css({"background":colorAccent});
	$('.vpl-themeColorText').css({"color":colorAccent});
	$('.vpl-playBtnBg').css({"background":colorAccent});
	if($(btn).hasClass('fa-random'))
	{
		$("#vpl-shuffleBtn.vpl-themeColor").css({"background":colorAccent});
		$("#vpl-shuffleBtn.vpl-themeColorText").css({"color":colorAccent});
		$("#vpl-shuffleBtn.vpl-playBtnBg").css({"background":colorAccent});
	}
	if($(btn).hasClass('fa-cog'))
	{
		$("#vpl-qualityBtn.vpl-themeColor").css({"background":colorAccent});
		$("#vpl-qualityBtn.vpl-themeColorText").css({"color":colorAccent});
		$("#vpl-qualityBtn.vpl-playBtnBg").css({"background":colorAccent});
	}
};
Video.fn.removeColorAccent = function(btn){
	if($(btn).hasClass('fa-random'))
	{
		$(".fa-random").css("color", "");
	}
	if($(btn).hasClass('fa-cog'))
	{
		$(".fa-cog").css("color", "");
	}
};
Video.fn.resizeAll = function(){
    var self = this;
	
    if(self.options.playerLayout == "fitToContainer" || self.options.playerLayout == "fitToBrowser")
    {
		//set responsive player height
		var height = this.parent.width()/(self.options.videoRatio);
		this.parent.height(height);

        switch(self.options.playlist){
            case "Right playlist":
                if(this.stretching){
                    if(this.parent.width()<380)
                        this.videoTrack.hide();
                    else
                        this.videoTrack.show();
                    if(this.parent.width()<361)
                        this.timeTotal.hide();
                    else
                        this.timeTotal.show();
					if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
					{
						if(this.parent.width()<320)
							this.qualityBtnWrapper.hide();
						else
							this.qualityBtnWrapper.show();
					}
					if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
						if(this.parent.width()<320)
							this.downloadBtnLink.hide();
						else
							this.downloadBtnLink.show();
					}
					if(this.parent.width()<290)
                        this.rewindBtnWrapper.hide();
                    else
                        this.rewindBtnWrapper.show();
					if(this.parent.width()<262)
						this.unmuteBtnWrapper.hide();
					else
						this.unmuteBtnWrapper.show();
					this.volumeTrack.show();
					if(self.options.embedShow=="Yes"){
						if(this.parent.width() < 560)
						self.embedBtn.hide();
						else
						self.embedBtn.show();
					}
                }
                else{
                    if(this.parent.width()<640)
                        this.videoTrack.hide();
                    else
                        this.videoTrack.show();
                    if(this.parent.width()<620)
                        this.timeTotal.hide();
                    else
                        this.timeTotal.show();
					if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
					{
						if(this.parent.width()<580)
							this.qualityBtnWrapper.hide();
						else
							this.qualityBtnWrapper.show();
					}
					if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
						if(this.parent.width()<580)
							this.downloadBtnLink.hide();
						else
							this.downloadBtnLink.show();
					}
                    if(this.parent.width()<552)
                        this.rewindBtnWrapper.hide();
                    else
                        this.rewindBtnWrapper.show();
                    if(this.parent.width()<452)
						this.unmuteBtnWrapper.hide();
                    else
						this.unmuteBtnWrapper.show();
                    if(this.parent.width()<425)
						this.volumeTrack.hide();
                    else
						this.volumeTrack.show();
					if(self.options.embedShow=="Yes"){
						if(this.parent.width() < 590)
						self.embedBtn.hide();
						else
						self.embedBtn.show();
					}
					//playlist resize
                    if(this.parent.width()<522){
						this.mainContainer.find(".vpl-playlistBarBtn").css({
							width:"20px"
						});
						this.mainContainer.find("#vpl-nextBtn").css({
							marginLeft:"-7.5px"
						});
						this.mainContainer.find("#vpl-previousBtn").css({
							marginLeft:"-7.5px"
						});
						this.mainContainer.find("#vpl-shuffleBtn").css({
							marginLeft:"-8px"
						});
						this._playlist.lastBtn.hide();
						this._playlist.firstBtn.hide();
                        this._playlist.playlist.css({width:90});
                        this.mainContainer.find(".vpl-itemRight").hide();
						
						this.videoTrack.show();
						this.timeElapsed.show();
						this.timeTotal.show();
						this.volumeTrack.show();
						this.rewindBtnWrapper.show();
						if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
							this.qualityBtnWrapper.show();
						if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes")
							this.downloadBtnLink.show();
						this.unmuteBtnWrapper.show();
						if(this.parent.width()<470)
							this.videoTrack.hide();
						else
							this.videoTrack.show();
						if(this.parent.width()<450)
							this.timeTotal.hide();
						else
							this.timeTotal.show();
						if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
						{
							if(this.parent.width()<410)
								this.qualityBtnWrapper.hide();
							else
								this.qualityBtnWrapper.show();
						}
						if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
							if(this.parent.width()<410)
								this.downloadBtnLink.hide();
							else
								this.downloadBtnLink.show();	
						}
						if(this.parent.width()<380)
							this.rewindBtnWrapper.hide();
						else
							this.rewindBtnWrapper.show();
						if(this.parent.width()<353)
							this.unmuteBtnWrapper.hide();
						else
							this.unmuteBtnWrapper.show();
						if(this.parent.width()<322)
							this.volumeTrack.hide();
						else
							this.volumeTrack.show();
                    }
                    else{
                        self._playlist.playlist.css({width:260});
                        self.mainContainer.find(".vpl-itemRight").show();
						this.mainContainer.find(".vpl-playlistBarBtn").css({
							width:"30px"
						});
						this.mainContainer.find("#vpl-nextBtn").css({
							marginLeft:"7.5px"
						});
						this.mainContainer.find("#vpl-previousBtn").css({
							marginLeft:"7.5px"
						});
						this.mainContainer.find("#vpl-shuffleBtn").css({
							marginLeft:"8px"
						});
						this._playlist.lastBtn.show();
						this._playlist.firstBtn.show();
                    }
                }
				//resizeing height
				if(this._playlist.playlist.height()<190 )
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:35
					})
					$(this._playlist.playlistBar).css({
						height:35
					})
				}
				else
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:36
					})
					$(this._playlist.playlistBar).css({
						height:36
					})
				}
				if(self.options.infoShow=="Yes"){
					if(this._playlist.playlist.height()<198)
						this.infoBtn.hide();
					else
						this.infoBtn.show();
				}
				if(self.options.embedShow=="Yes"){
					if(this._playlist.playlist.height()<159)
						this.embedBtn.hide();
					else
						this.embedBtn.show();
				}
				if(self.options.shareShow=="Yes"){
					if(this._playlist.playlist.height()<123)
						this.shareBtn.hide();
					else
						this.shareBtn.show();
				}
                break;
            case "Bottom playlist":
                
				if(this.mainContainer.height()<380)
					this.videoTrack.hide();
				else
					this.videoTrack.show();
				if(this.mainContainer.height()<361)
					this.timeTotal.hide();
				else
					this.timeTotal.show();
				if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
				{
					if(this.mainContainer.height()<320)
						this.qualityBtnWrapper.hide();
					else
						this.qualityBtnWrapper.show();
				}
				if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
					if(this.mainContainer.height()<320)
						this.downloadBtnLink.hide();
					else
						this.downloadBtnLink.show();
				}
				if(this.mainContainer.height()<290)
					this.rewindBtnWrapper.hide();
				else
					this.rewindBtnWrapper.show();
				if(this.mainContainer.height()<262)
					this.unmuteBtnWrapper.hide();
				else
					this.unmuteBtnWrapper.show();
				this.volumeTrack.show();
				if(self.options.embedShow=="Yes"){
					// if(this.mainContainer.height() < 330)
					// self.embedBtn.hide();
					// else
					// self.embedBtn.show();
				}
				
				//resizeing height
				if(this.mainContainer.height()<313 )
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:35
					})
					$(this._playlist.playlistBar).css({
						height:35
					})
					$(this._playlist.playlist).css({
						height:127
					})
					this._playlist.playlistH = $(this._playlist.playlist).height()
					
					
						//third level resizeing
						if(this.mainContainer.height()<230 )
						{
							$(this._playlist.playlist).css({
								height:92
							})
							this._playlist.playlistH = $(this._playlist.playlist).height()
							this.mainContainer.find(".vpl-itemRight_bottom").hide();
							this.mainContainer.find(".vpl-nowPlayingThumbnail").css({
								opacity: 0
							});
							this.mainContainer.find(".vpl-itemSelected").css({
								width: 40,
								height:40
							});
							this.mainContainer.find(".vpl-itemUnselected").css({
								width: 40,
								height:40
							});
							this.mainContainer.find(".vpl-itemLeft").css({
								width: 35,
								height: 35
							});
						}
						else{
							this.mainContainer.find(".vpl-itemRight_bottom").show();
							this.mainContainer.find(".vpl-nowPlayingThumbnail").css({
								opacity: 1
							});
							this.mainContainer.find(".vpl-itemSelected").css({
								width: 245,
								height:76
							});
							this.mainContainer.find(".vpl-itemUnselected").css({
								width: 245,
								height:76
							});
							this.mainContainer.find(".vpl-itemLeft").css({
								width: 70,
								height: 70
							});
						}
				}
				else
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:50
					})
					$(this._playlist.playlistBar).css({
						height:50
					})
					$(this._playlist.playlist).css({
						height:142
					})
					this._playlist.playlistH = $(this._playlist.playlist).height()
					
					this.mainContainer.find(".vpl-itemRight_bottom").show();
					this.mainContainer.find(".vpl-nowPlayingThumbnail").css({
						opacity: 1
					});
					this.mainContainer.find(".vpl-itemSelected").css({
						width: 245,
						height:76
					});
					this.mainContainer.find(".vpl-itemUnselected").css({
						width: 245,
						height:76
					});
					this.mainContainer.find(".vpl-itemLeft").css({
						width: 70,
						height: 70
					});
				}
				if(self.options.infoShow=="Yes"){
					if(this.mainContainer.height()<270)
						this.infoBtn.hide();
					else
						this.infoBtn.show();
				}
				if(self.options.embedShow=="Yes"){
					if(this.mainContainer.height()<330)
						this.embedBtn.hide();
					else
						this.embedBtn.show();
				}
				if(self.options.shareShow=="Yes"){
					if(this.mainContainer.height()<194)
						this.shareBtn.hide();
					else
						this.shareBtn.show();
				}
				
            case "Off":
                if(self.options.embedShow=="Yes"){
					// if(this.parent.width() < 550)
						// self.embedBtn.hide();
					// else
						// self.embedBtn.show();
				}
                if(this.parent.width() < 378)
                    self.videoTrack.hide();
                else
                    self.videoTrack.show();
                if(this.parent.width() < 360)
                    self.timeTotal.hide();
                else
                    self.timeTotal.show();
				if(this.parent.width() < 289)
                    self.rewindBtnWrapper.hide();
                else
                    self.rewindBtnWrapper.show();
				if(this.parent.width() < 262)
                    this.unmuteBtnWrapper.hide();
				else
					this.unmuteBtnWrapper.show();
                break;

        }
		
        if(this.stretching){
            if(self.options.playlist=="Right playlist"){
                self.element.width(self.parent.parent().width());
                self.element.height(self._playlist.playlist.height());
            }
            else if(self.options.playlist=="Bottom playlist"){
                self.element.width(self.parent.parent().width());
                // self.element.height(self.parent.parent().height());
				self.element.height(height);
            }
            else if(self.options.playlist=="Off"){
                self.element.width(self.parent.parent().width());
                self.element.height(self.parent.parent().height());
            }
        }
        else{
            if(self.options.playlist=="Right playlist"){
                self.element.width(self.parent.parent().width()-self._playlist.playlist.width());
                self.element.height(self._playlist.playlist.height());
            }
            else if(self.options.playlist=="Bottom playlist"){
                self.element.width(self.parent.parent().width());
                // self.element.height(self.parent.parent().height()-self._playlist.playlist.height());
                self.element.height(height-self._playlist.playlist.height());
            }
            else if(self.options.playlist=="Off"){
                self.element.width(self.parent.parent().width());
                self.element.height(self.parent.height());
            }
        }
		if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
		{
			if(!self.options.showAllControls){
				self.videoElement.width(self.element.width());
				self.videoElement.height(self.element.height());
				self.videoElementAD.width(self.element.width());
				self.videoElementAD.height(self.element.height());
			}
		}
        self._playlist.resizePlaylist();
        self.elementAD.width(self.element.width());
        self.elementAD.height(self.element.height());
        self.resizeVideoTrack();
        self.positionOverScreenButtons();
        self.resizeBars();
        self.positionLogo();
        self.positionPopup();
        // self.positionPoster();
    }

    else if(self.options.playerLayout == "fixedSize"){//fixed width/height

	self.newPlayerWidth = $(window).width() - self.mainContainer.position().left -48;
	/*self.newPlayerHeight = self.newPlayerWidth/(16/9);*/
	self.newPlayerHeight = self.newPlayerWidth/(self.options.videoPlayerWidth/self.options.videoPlayerHeight);
    if ( self.newPlayerWidth < self.options.videoPlayerWidth )
    { 
		//lightbox resize
		if(this.options.lightBox){
			$(self.mainContainer).css({
				position: 'absolute',
				left: 24,
				top: window.innerHeight/2 - (self.newPlayerHeight/2) - 10
			})
		}
		
        switch(self.options.playlist){
            case "Right playlist":

                    if(this.stretching){
						if(self.newPlayerWidth<380)
							this.videoTrack.hide();
						else
							this.videoTrack.show();
						if(self.newPlayerWidth<361)
							this.timeTotal.hide();
						else
							this.timeTotal.show();
						if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
						{
							if(self.newPlayerWidth<320)
								this.qualityBtnWrapper.hide();
							else
								this.qualityBtnWrapper.show();
						}
						if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
							if(self.newPlayerWidth<320)
								this.downloadBtnLink.hide();
							else
								this.downloadBtnLink.show();
						}
						if(self.newPlayerWidth<290)
							this.rewindBtnWrapper.hide();
						else
							this.rewindBtnWrapper.show();
						if(self.newPlayerWidth<262)
							this.unmuteBtnWrapper.hide();
						else
							this.unmuteBtnWrapper.show();
						this.volumeTrack.show();
						if(self.options.embedShow=="Yes"){
							if(self.newPlayerWidth < 560)
							self.embedBtn.hide();
							else
							self.embedBtn.show();
						}
                    }
                    //no stretching
                    else{
						if(self.newPlayerWidth<640)
							this.videoTrack.hide();
						else
							this.videoTrack.show();
						if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
							if(self.newPlayerWidth < 584)
								self.downloadBtnLink.hide();
							else
								self.downloadBtnLink.show();
						}
						if(self.newPlayerWidth < 620)
                            self.timeTotal.hide();
                        else
                            self.timeTotal.show();
						if(self.options.embedShow=="Yes"){
							if(self.newPlayerWidth < 655)
								self.embedBtn.hide();
							else
								self.embedBtn.show();
						}
						if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
						{
							if(self.newPlayerWidth < 580)
								self.qualityBtnWrapper.hide();
							else
								self.qualityBtnWrapper.show();
						}
                        if(self.newPlayerWidth < 550)
                            self.rewindBtnWrapper.hide();
                        else
                            self.rewindBtnWrapper.show();
                        if(self.newPlayerWidth < 525)
                            self.unmuteBtnWrapper.hide();
                        else
                            self.unmuteBtnWrapper.show();
						//playlist resize
						if(self.newPlayerWidth<522){
							this.mainContainer.find(".vpl-playlistBarBtn").css({
								width:"20px"
							});
							this.mainContainer.find("#vpl-nextBtn").css({
								marginLeft:"-7.5px"
							});
							this.mainContainer.find("#vpl-previousBtn").css({
								marginLeft:"-7.5px"
							});
							this.mainContainer.find("#vpl-shuffleBtn").css({
								marginLeft:"-8px"
							});
							this._playlist.lastBtn.hide();
							this._playlist.firstBtn.hide();
							this._playlist.playlist.css({width:90});
							this.mainContainer.find(".vpl-itemRight").hide();
							
							this.videoTrack.show();
							this.timeElapsed.show();
							if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes")
								this.downloadBtnLink.show();
							this.timeTotal.show();
							this.volumeTrack.show();
							this.rewindBtnWrapper.show();
							if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
								this.qualityBtnWrapper.show();
							this.unmuteBtnWrapper.show();
							if(self.newPlayerWidth<470)
								this.videoTrack.hide();
							else
								this.videoTrack.show();
							if(self.newPlayerWidth<450)
								this.timeTotal.hide();
							else
								this.timeTotal.show();	
							if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
							{
								if(self.newPlayerWidth<410)
									this.qualityBtnWrapper.hide();
								else
									this.qualityBtnWrapper.show();
							}
							if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
								if(self.newPlayerWidth<410)
									this.downloadBtnLink.hide();
								else
									this.downloadBtnLink.show();
							}
							if(self.newPlayerWidth<380)
								this.rewindBtnWrapper.hide();
							else
								this.rewindBtnWrapper.show();
							if(self.newPlayerWidth<353)
								this.unmuteBtnWrapper.hide();
							else
								this.unmuteBtnWrapper.show();
							if(self.newPlayerWidth<322)
								this.volumeTrack.hide();
							else
								this.volumeTrack.show();
						}
						else{
							self._playlist.playlist.css({width:260});
							self.mainContainer.find(".vpl-itemRight").show();
							this.mainContainer.find(".vpl-playlistBarBtn").css({
								width:"30px"
							});
							this.mainContainer.find("#vpl-nextBtn").css({
								marginLeft:"7.5px"
							});
							this.mainContainer.find("#vpl-previousBtn").css({
								marginLeft:"7.5px"
							});
							this.mainContainer.find("#vpl-shuffleBtn").css({
								marginLeft:"8px"
							});
							this._playlist.lastBtn.show();
							this._playlist.firstBtn.show();
						}
                    }
					//resizeing height
					if(this.newPlayerHeight<190 )
					{
						$(this.playButtonScreen).css({
						  '-webkit-transform' : 'scale(' + .6 + ')',
						  '-moz-transform'    : 'scale(' + .6 + ')',
						  '-ms-transform'     : 'scale(' + .6 + ')',
						  '-o-transform'      : 'scale(' + .6 + ')',
						  'transform'         : 'scale(' + .6 + ')'
						});
						$(this.toggleAdPlayBox).css({
						  '-webkit-transform' : 'scale(' + .6 + ')',
						  '-moz-transform'    : 'scale(' + .6 + ')',
						  '-ms-transform'     : 'scale(' + .6 + ')',
						  '-o-transform'      : 'scale(' + .6 + ')',
						  'transform'         : 'scale(' + .6 + ')'
						});
						$(this.skipAdCount).css({
						  '-webkit-transform' : 'scale(' + .6 + ')',
						  '-moz-transform'    : 'scale(' + .6 + ')',
						  '-ms-transform'     : 'scale(' + .6 + ')',
						  '-o-transform'      : 'scale(' + .6 + ')',
						  'transform'         : 'scale(' + .6 + ')',
						  'transform-origin'  : 'bottom right'
						});
						$(this.skipAdBox).css({
						  '-webkit-transform' : 'scale(' + .6 + ')',
						  '-moz-transform'    : 'scale(' + .6 + ')',
						  '-ms-transform'     : 'scale(' + .6 + ')',
						  '-o-transform'      : 'scale(' + .6 + ')',
						  'transform'         : 'scale(' + .6 + ')',
						  'transform-origin'  : 'bottom right'
						});
						$(this.controls).css({
							height:35
						})
						$(this._playlist.playlistBar).css({
							height:35
						})
					}
					else
					{
						$(this.playButtonScreen).css({
						  '-webkit-transform' : 'scale(' + 1 + ')',
						  '-moz-transform'    : 'scale(' + 1 + ')',
						  '-ms-transform'     : 'scale(' + 1 + ')',
						  '-o-transform'      : 'scale(' + 1 + ')',
						  'transform'         : 'scale(' + 1 + ')'
						});
						$(this.toggleAdPlayBox).css({
						  '-webkit-transform' : 'scale(' + 1 + ')',
						  '-moz-transform'    : 'scale(' + 1 + ')',
						  '-ms-transform'     : 'scale(' + 1 + ')',
						  '-o-transform'      : 'scale(' + 1 + ')',
						  'transform'         : 'scale(' + 1 + ')'
						});
						$(this.skipAdCount).css({
						  '-webkit-transform' : 'scale(' + 1 + ')',
						  '-moz-transform'    : 'scale(' + 1 + ')',
						  '-ms-transform'     : 'scale(' + 1 + ')',
						  '-o-transform'      : 'scale(' + 1 + ')',
						  'transform'         : 'scale(' + 1 + ')',
						  'transform-origin'  : 'bottom right'
						});
						$(this.skipAdBox).css({
						  '-webkit-transform' : 'scale(' + 1 + ')',
						  '-moz-transform'    : 'scale(' + 1 + ')',
						  '-ms-transform'     : 'scale(' + 1 + ')',
						  '-o-transform'      : 'scale(' + 1 + ')',
						  'transform'         : 'scale(' + 1 + ')',
						  'transform-origin'  : 'bottom right'
						});
						$(this.controls).css({
							height:50
						})
						$(this._playlist.playlistBar).css({
							height:50
						})
					}
					if(self.options.infoShow=="Yes"){
						if(self.newPlayerHeight<198)
							this.infoBtn.hide();
						else
							this.infoBtn.show();
					}
					if(self.options.embedShow=="Yes"){
						if(self.newPlayerHeight<159)
							this.embedBtn.hide();
						else
							this.embedBtn.show();
					}
					if(self.options.shareShow=="Yes"){
						if(self.newPlayerHeight<123)
							this.shareBtn.hide();
						else
							this.shareBtn.show();
					}
            break;

            case "Bottom playlist":
			
				if(self.newPlayerWidth<380)
					this.videoTrack.hide();
				else
					this.videoTrack.show();
				if(self.newPlayerWidth<361)
					this.timeTotal.hide();
				else
					this.timeTotal.show();
				if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
				{
					if(self.newPlayerWidth<320)
						this.qualityBtnWrapper.hide();
					else
						this.qualityBtnWrapper.show();
				}
				if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
					if(self.newPlayerWidth<320)
						this.downloadBtnLink.hide();
					else
						this.downloadBtnLink.show();
				}
				if(self.newPlayerWidth<290)
					this.rewindBtnWrapper.hide();
				else
					this.rewindBtnWrapper.show();
				if(self.newPlayerWidth<262)
					this.unmuteBtnWrapper.hide();
				else
					this.unmuteBtnWrapper.show();
				this.volumeTrack.show();
				if(self.options.embedShow=="Yes"){
					if(self.newPlayerWidth < 560)
					self.embedBtn.hide();
					else
					self.embedBtn.show();
				}
				
                //resizeing height
				if(this.newPlayerHeight<313 )
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:35
					})
					$(this._playlist.playlistBar).css({
						height:35
					})
					$(this._playlist.playlist).css({
						height:127
					})
					this._playlist.playlistH = $(this._playlist.playlist).height()
					
					
						//third level resizeing
						if(this.newPlayerHeight<230 )
						{
							$(this._playlist.playlist).css({
								height:92
							})
							this._playlist.playlistH = $(this._playlist.playlist).height()
							this.mainContainer.find(".vpl-itemRight_bottom").hide();
							this.mainContainer.find(".vpl-nowPlayingThumbnail").css({
								opacity: 0
							});
							this.mainContainer.find(".vpl-itemSelected").css({
								width: 40,
								height:40
							});
							this.mainContainer.find(".vpl-itemUnselected").css({
								width: 40,
								height:40
							});
							this.mainContainer.find(".vpl-itemLeft").css({
								width: 35,
								height: 35
							});
						}
						else{
							this.mainContainer.find(".vpl-itemRight_bottom").show();
							this.mainContainer.find(".vpl-nowPlayingThumbnail").css({
								opacity: 1
							});
							this.mainContainer.find(".vpl-itemSelected").css({
								width: 245,
								height:76
							});
							this.mainContainer.find(".vpl-itemUnselected").css({
								width: 245,
								height:76
							});
							this.mainContainer.find(".vpl-itemLeft").css({
								width: 70,
								height: 70
							});
						}
				}
				else
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:50
					})
					$(this._playlist.playlistBar).css({
						height:50
					})
					$(this._playlist.playlist).css({
						height:142
					})
					this._playlist.playlistH = $(this._playlist.playlist).height()
				}
				if(self.options.infoShow=="Yes"){
					if(self.newPlayerHeight<198)
						this.infoBtn.hide();
					else
						this.infoBtn.show();
				}
				if(self.options.embedShow=="Yes"){
					if(self.newPlayerHeight<159)
						this.embedBtn.hide();
					else
						this.embedBtn.show();
				}
				if(self.options.shareShow=="Yes"){
					if(self.newPlayerHeight<123)
						this.shareBtn.hide();
					else
						this.shareBtn.show();
				}
            break;

            case "Off":
                if(self.newPlayerWidth<380)
					this.videoTrack.hide();
				else
					this.videoTrack.show();
				if(self.newPlayerWidth<361)
					this.timeTotal.hide();
				else
					this.timeTotal.show();
				if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
				{
					if(self.newPlayerWidth<320)
						this.qualityBtnWrapper.hide();
					else
						this.qualityBtnWrapper.show();
				}
				if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes"){
					if(self.newPlayerWidth<320)
						this.downloadBtnLink.hide();
					else
						this.downloadBtnLink.show();
				}
				if(self.newPlayerWidth<290)
					this.rewindBtnWrapper.hide();
				else
					this.rewindBtnWrapper.show();
				if(self.newPlayerWidth<262)
					this.unmuteBtnWrapper.hide();
				else
					this.unmuteBtnWrapper.show();
				this.volumeTrack.show();
				if(self.options.embedShow=="Yes"){
					if(self.newPlayerWidth < 560)
					self.embedBtn.hide();
					else
					self.embedBtn.show();
				}
				//resizeing height
				if(this.newPlayerHeight<190 )
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + .6 + ')',
					  '-moz-transform'    : 'scale(' + .6 + ')',
					  '-ms-transform'     : 'scale(' + .6 + ')',
					  '-o-transform'      : 'scale(' + .6 + ')',
					  'transform'         : 'scale(' + .6 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:35
					})
					$(this._playlist.playlistBar).css({
						height:35
					})
				}
				else
				{
					$(this.playButtonScreen).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.toggleAdPlayBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')'
					});
					$(this.skipAdCount).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.skipAdBox).css({
					  '-webkit-transform' : 'scale(' + 1 + ')',
					  '-moz-transform'    : 'scale(' + 1 + ')',
					  '-ms-transform'     : 'scale(' + 1 + ')',
					  '-o-transform'      : 'scale(' + 1 + ')',
					  'transform'         : 'scale(' + 1 + ')',
					  'transform-origin'  : 'bottom right'
					});
					$(this.controls).css({
						height:50
					})
					$(this._playlist.playlistBar).css({
						height:50
					})
				}
				if(self.options.infoShow=="Yes"){
					if(self.newPlayerHeight<198)
						this.infoBtn.hide();
					else
						this.infoBtn.show();
				}
				if(self.options.embedShow=="Yes"){
					if(self.newPlayerHeight<159)
						this.embedBtn.hide();
					else
						this.embedBtn.show();
				}
				if(self.options.shareShow=="Yes"){
					if(self.newPlayerHeight<123)
						this.shareBtn.hide();
					else
						this.shareBtn.show();
				}
            break;
            }
    }
    else
    {
		//lightbox resize
		if(this.options.lightBox){
			$(self.mainContainer).css({
				position: 'absolute',
				left: window.innerWidth/2 - (self.options.videoPlayerWidth/2),
				top: window.innerHeight/2 - (self.options.videoPlayerHeight/2) - 10
			})
		}
		//initial fixed size
        self.newPlayerWidth = self.options.videoPlayerWidth;
		self.newPlayerHeight = self.options.videoPlayerHeight;
		this.videoTrack.show();
		this.timeElapsed.show();
		this.timeTotal.show();
		this.volumeTrack.show();
		this.rewindBtnWrapper.show();
		if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
			this.qualityBtnWrapper.show();
		this.unmuteBtnWrapper.show();self._playlist.playlist.css({width:260});
		this.mainContainer.find(".vpl-itemRight").show();
		this.mainContainer.find(".vpl-playlistBarBtn").css({
			width:"30px"
		});
		this._playlist.lastBtn.show();
		this._playlist.firstBtn.show();
    }

    if(self.options.playlist=="Right playlist"){
		if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
		{
			if(!self.options.showAllControls){
				self.videoElement.height(self.newPlayerHeight-50);
				self.videoElementAD.height(self.newPlayerHeight-50);
			}
		}
        self.element.css({width:self.newPlayerWidth, height:self.newPlayerHeight});
        self.mainContainer.css({width:self.newPlayerWidth, height:self.newPlayerHeight});
    }
    else if(self.options.playlist=="Bottom playlist"){
		if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
		{
			//self.videoElement.width(self.newPlayerWidth-32);
			//self.videoElementAD.width(self.newPlayerWidth-32);
		}

        self.element.width(self.newPlayerWidth);
        self.mainContainer.css({width:self.newPlayerWidth, height:self.newPlayerHeight});
    }
    else if(self.options.playlist=="Off"){
		if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
		{
			if(!self.options.showAllControls){
				self.videoElement.height(self.newPlayerHeight-50);
				self.videoElementAD.height(self.newPlayerHeight-50);
			}
		}
        self.element.css({width:self.newPlayerWidth, height:self.newPlayerHeight});
        self.mainContainer.css({width:self.newPlayerWidth, height:self.newPlayerHeight});
    }
    if(this.stretching)
    {
        if(self.options.playlist=="Right playlist")
        {
			if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
			{
				if(!self.options.showAllControls){
					self.videoElement.width(self.newPlayerWidth-32);
					self.videoElementAD.width(self.newPlayerWidth-32);
				}
			}
			self.element.width($(self.mainContainer).width());
        }
        else if(self.options.playlist=="Bottom playlist")
        {
			if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
			{
				//self.videoElement.height(self.newPlayerHeight + self._playlist.playlistH-50);
				//self.videoElementAD.height(self.newPlayerHeight + self._playlist.playlistH-50);
			}            
            self.element.height(self.newPlayerHeight);
        }
        else if(self.options.playlist=="Off")
        {
			if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
			{
				self.videoElement.width(self.newPlayerWidth-32);
				self.videoElementAD.width(self.newPlayerWidth-32);
			}
			self.element.width($(self.mainContainer).width());
        }
    }
    else
    {
        if(self.options.playlist=="Right playlist")
        {
			if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
			{
				if(!self.options.showAllControls){
					self.videoElement.width(self.newPlayerWidth- self._playlist.playlist.width()-32);
					self.videoElementAD.width(self.newPlayerWidth- self._playlist.playlist.width()-32);
				}
			}
			self.element.width($(self.mainContainer).width()- self._playlist.playlist.width());
            self._playlist.resizePlaylist(self.newPlayerWidth, self.newPlayerHeight);
        }
        else if(self.options.playlist=="Bottom playlist")
        {
			if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
			{
				//self.videoElement.height(self.newPlayerHeight-50);
				//self.videoElementAD.height(self.newPlayerHeight-50);
			}

            self.element.height(self.newPlayerHeight - self._playlist.playlistH);
            self._playlist.resizePlaylist(self.newPlayerWidth, self.newPlayerHeight);

        }
        else if(self.options.playlist=="Off")
        {
			if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)"))
			{
				if(!self.options.showAllControls){
					self.videoElement.width(self.newPlayerWidth-32);
					self.videoElementAD.width(self.newPlayerWidth-32);
				}
			}
			self.element.width($(self.mainContainer).width());
        }
    }

    self.elementAD.width(self.element.width());
    self.elementAD.height(self.element.height());

	if (self.agentID && (self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")) {
		if(self.playBtnScreen)
		self.playBtnScreen.hide();
	}
	if(self.youtubePlayer!= undefined)
	{
		if(self.realFullscreenActive)
		{
			self.element.width($(document).width());
			self.element.height($(document).height());
		}
		self.youtubePlayer.setSize("100%","100%" );
	}
	if(this.options.lightBox){
		$(this.mainContainerBG).css({
			width: $(self.mainContainer).width() + 20,
			height: $(self.mainContainer).height() + 20
		})	
	}
    self.resizeVideoTrack();
    self.positionOverScreenButtons();
    self.resizeBars();
    self.positionLogo();
    self.positionPopup();
    // self.positionPoster();
	}
};
Video.fn.autohideControls = function(){
    var element  = this.element;
    var idle     = false;
    var timeout  = this.options.autohideControls*1000;
    var interval = 1000;
    var timeFromLastEvent = 0;

    var reset = function()
    {
        if (idle)
            element.trigger("idle", false);
        idle = false;
        timeFromLastEvent = 0;
    };

    var check = function()
    {
        if (timeFromLastEvent >= timeout) {
            reset();
            idle = true;
            element.trigger("idle", true);
        }
        else
        {
            timeFromLastEvent += interval;
        }
    };

    element.bind(idleEvents, reset);

    var loop = setInterval(check, interval);

    element.on("unload",function()
    {
        clearInterval(loop);
    });
};
Video.fn.resizeBars = function(){

	if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
	{
		if(this.youtubePlayer!= undefined && this._playlist.youtubeSTARTED){
			//progress
			this.progressWidth = (this.youtubePlayer.getCurrentTime()/this.youtubePlayer.getDuration() )*this.videoTrack.width();
			this.videoTrackProgress.css("width", this.progressWidth);
			this.progressIdleWidth = (this.youtubePlayer.getCurrentTime()/this.youtubePlayer.getDuration() )*this.progressIdleTrack.width();
			this.progressIdle.css("width", this.progressIdleWidth);
			//download
			this.buffered = this.youtubePlayer.getVideoLoadedFraction();
			this.downloadWidth = (this.buffered )*this.videoTrack.width();
			this.videoTrackDownload.css("width", this.downloadWidth);
			this.progressIdleDownloadWidth = (this.buffered)*this.progressIdleTrack.width();
			this.progressIdleDownload.css("width", this.progressIdleDownloadWidth);
		}
	}
	else if(this._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || this.options.videoType=="HTML5 (self-hosted)")
    {
		this.downloadWidth = (this.buffered/this.video.duration )*this.videoTrack.width();
		this.videoTrackDownload.css("width", this.downloadWidth);

		this.progressWidth = (this.video.currentTime/this.video.duration )*this.videoTrack.width();
		this.videoTrackProgress.css("width", this.progressWidth);
		
		this.progressIdleDownloadWidth = (this.buffered/this.video.duration )*this.progressIdleTrack.width();
		this.progressIdleDownload.css("width", this.progressIdleDownloadWidth);
		
		this.progressIdleWidth = (this.video.currentTime/this.video.duration )*this.progressIdleTrack.width();
		this.progressIdle.css("width", this.progressIdleWidth);

		this.progressWidthAD = (this.videoAD.currentTime/this.videoAD.duration )*this.elementAD.width();
		this.progressAD.css("width", this.progressWidthAD);
	}
};
Video.fn.createPopup = function(){
	var self = this;
	//popup ads
    this.adImg = $("");

    this.image = new Image();
    this.image.src = this._playlist.videos_array[this._playlist.videoid].popupImg;

    $(this.image).on("load",function() {
        self.adImg.append(self.image);
        self.positionPopup();
        self.adImg.append(self.adClose);
    });
    this.element.append(this.adImg);
    this.adImg.hide();
    this.adImg.css({opacity:0});
	this.popupBtnClose = $("<div />");
    this.popupBtnClose.addClass("vpl-btnClose vpl-themeColorText");
    this.infoWindow.append(this.popupBtnClose);
    this.popupBtnClose.css({bottom:0});
	this.adImg.append(this.popupBtnClose);

    this.popupBtnCloseIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("fa-close")
		.addClass("vpl-themeColor");
    this.popupBtnClose.append(this.popupBtnCloseIcon);

    this.popupBtnClose.bind(this.CLICK_EV,$.proxy(function()
    {
		self.adOn=true;
        self.togglePopup();
    }, this));

    this.popupBtnClose.mouseover(function(){
        $(this).stop().animate({
            opacity:0.7
        },200);
    });
    this.popupBtnClose.mouseout(function(){
        $(this).stop().animate({
            opacity:1
        },200);
    });
}
Video.fn.positionPopup = function(){
	
    var self=this;
	
    this.adImg.css({
        bottom: self.controls.height() + 20,
        left: self.element.width()/2 - this.adImg.width()/2
    });
};
Video.fn.newAd = function(){
	
    var self = this;
	
    this.adImg.hide();
    this.image.src="";
    this.image.src=this._playlist.videos_array[this._playlist.videoid].popupImg;

	if(this.adOn)
		return
	
    $(this.image).bind(this.START_EV, function(){
		
        window.open(self._playlist.videos_array[self._playlist.videoid].popupAdGoToLink);
		
        if(self._playlist.videos_array[self._playlist.videoid].videoType == "youtube" || self.options.videoType=="YouTube"){
			self.youtubePlayer.pauseVideo();
		}
        if(self._playlist.videos_array[self._playlist.videoid].videoType == "HTML5" || self.options.videoType=="HTML5 (self-hosted)"){
			self.pause();
		}
        if(self._playlist.videos_array[self._playlist.videoid].videoType == "vimeo" || self.options.videoType=="Vimeo"){
			self._playlist.vimeoPlayer.api('pause');
		}
	})
};
Video.fn.createLogo = function(){
        var self=this;
        this.logoImg = $("<div/>");
        this.logoImg.addClass("vpl-logo");
        this.img = new Image();
        this.img.src = self.options.logoPath;
        //
        $(this.img).on("load",function() {
            self.logoImg.append(self.img);
            self.positionLogo();
        });

        if(self.options.logoShow=="Yes")
        {
            // this.element.append(this.logoImg);
        }

        if(self.options.logoClickable=="Yes")
        {
            this.logoImg.bind(this.CLICK_EV,$.proxy(function(){
                window.open(self.options.logoGoToLink);
            }, this));

            this.logoImg.mouseover(function(){
                $(this).stop().animate({opacity:0.8},200);
            });
            this.logoImg.mouseout(function(){
                $(this).stop().animate({opacity:1},200);
            });
            $('.vpl-logo').css('cursor', 'pointer');
        }
};
Video.fn.positionLogo = function(){
    var self=this;
	var bottomMargin;
	
	if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
	bottomMargin=70;
	else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
	bottomMargin=70;
	else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo" || self.options.videoType=="Vimeo")
	bottomMargin=55;
	
    if(self.options.logoPosition == "bottom-right")
    {
        this.logoImg.css({
            bottom:  bottomMargin,
            right: buttonsMargin
        });
    }
    else if(self.options.logoPosition == "bottom-left")
    {
        this.logoImg.css({
            bottom:  bottomMargin,
            left: buttonsMargin
        });
    }
};
Video.fn.showVideoElements = function()
{
    this.videoElement.show();
    this.videoElementAD.show();
};
Video.fn.hideVideoElements = function(){
    this.videoElement.hide();
    this.videoElementAD.hide();
};
Video.fn.createAds = function(){
    var self=this;
    adsImg = $("<div/>");
    adsImg.addClass("ads");

    image = new Image();
    image.src = self._playlist.videos_array[0].adsPath;

    $(image).on("load",function() {
        adsImg.append(image);
        self.positionAds();
    });
    this.element.append(adsImg);
    adsImg.hide();
};
Video.fn.positionAds = function(){
    var self=this;
    adsImg.css({
        bottom: self.controls.height()+5,
        left: self.element.width()/2-adsImg.width()/2
    });
};
Video.fn.setupAutoplay = function()
{
   var self=this;
	if(this.options.lightBox)
		return
	
    if(self.options.autoplay)
    {
		// self.play();
		
		if(self.isMobile.any())
			self.playButtonScreen.show();
		else
			self.play();
    }
    else if(!self.options.autoplay)
    {
        self.pause();
        self.preloader.hide();
    }
}
Video.fn.createNowPlayingText = function()
{
	var self=this;
	
	if(self.options.loadRandomVideoOnStart=="Yes")
        this.nowPlayingTitle.append('<p class="vpl-nowPlayingText vpl-nowPlayingText'+" "+this.options.instanceTheme+'">' + this._playlist.videos_array[self._playlist.rand].title + '</p>');
    else
        this.nowPlayingTitle.append('<p class="vpl-nowPlayingText vpl-nowPlayingText'+" "+this.options.instanceTheme+'">' + this._playlist.videos_array[0].title + '</p>');

	this.nowPlayingTitleW=this.nowPlayingTitle.width();
	
    if(this.options.nowPlayingText=="No")
        this.nowPlayingTitle.hide();
};
Video.fn.createInfoWindowContent = function()
{
	var self=this;
	if(self.options.loadRandomVideoOnStart=="Yes"){
        this.infoWindow.append('<p class="vpl-infoTitle vpl-themeColorText vpl-titles">' + this._playlist.videos_array[self._playlist.rand].title + '</p>');
        this.infoWindow.append('<p class="vpl-infoText vpl-infoText'+" "+this.options.instanceTheme+'">' + this._playlist.videos_array[self._playlist.rand].info_text + '</p>');
    }
    else{
        this.infoWindow.append('<p class="vpl-infoTitle vpl-themeColorText vpl-titles">' + this._playlist.videos_array[0].title + '</p>');
        this.infoWindow.append('<p class="vpl-infoText vpl-infoText'+" "+this.options.instanceTheme+'">' + this._playlist.videos_array[0].info_text + '</p>');
    }
	
	this.infoWindow.css({
		top:-(this.infoWindow.height())
	}).hide();
};
Video.fn.createSkipAd = function(){
    var self=this;

    this.skipAdBox = $("<div />")
        .addClass("vpl-skipAdBox")
        .bind(self.CLICK_EV, function(){
            self.closeAD();
        })
        .hide();
    this.elementAD.append(this.skipAdBox);
	
	this.skipAdBoxContentLeft = $("<div />")
        .addClass("vpl-skipAdBoxContentLeft");
	this.skipAdBox.append(this.skipAdBoxContentLeft);
	
    this.skipAdBoxContentLeft.append('<p class="vpl-skipAdTitle">' + this.options.skipAdvertisementText + '</p>');
	
	this.skipAdBoxIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("fa-step-forward-ad")
    this.skipAdBox.append(this.skipAdBoxIcon);
};
Video.fn.createSkipAdCount = function(){
    var self=this;
	
	this.skipAdCount = $("<div />")
        .addClass("vpl-skipAdCount")
		.hide();
    this.elementAD.append(this.skipAdCount);
	
	this.i = document.createElement('img');
	this.i.src = self._playlist.videos_array[self._playlist.videoid].thumbnail_image;
	this.skipAdCount.append(this.i);
	$('.vpl-skipAdCount img').addClass('vpl-skipAdCountImage vpl-themeColorThumbBorder');
	
	this.skipAdCountContentLeft = $("<div />")
        .addClass("vpl-skipAdCountContentLeft");
	this.skipAdCount.append(this.skipAdCountContentLeft);
		
	this.skipAdCountContentLeft.append('<p class="vpl-skipAdCountTitle">' + "" + '</p>');
	
	this.skipAdCount.css({
		right:-(this.skipAdCount.width()),
		bottom:28
	}).hide();
};
Video.fn.createAdTogglePlay = function(){
    var self=this;

    this.toggleAdPlayBox = $("<div />")
        .addClass("vpl-toggleAdPlayBox")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("fa-play"+" "+this.options.instanceTheme)
        .bind(self.CLICK_EV, function(){
            self.togglePlayAD();
			self.ADTriggered=true;//ad is once enabled
        })
        .hide()
    this.elementAD.append(this.toggleAdPlayBox);
};
Video.fn.createVideoAdTitleInsideAD = function(){
    var self=this;
    this.videoAdBoxInside = $("<div />");
    this.videoAdBoxInside.addClass("vpl-videoAdBoxInside");
    this.elementAD.append(this.videoAdBoxInside);

    this.videoAdBoxInside.append('<div class="vpl-adsTitleInside">' + this.options.advertisementTitle + " "  + '</div>');
    this.videoAdBoxInside.append(this.timeLeftInside);
    this.videoAdBoxInside.hide();
};
Video.fn.attachZeroClipboard = function()
{
	var self=this;
	$(this.copy).each(function loopingItems()
    {
        self.zeroClipboard = ZeroClipboard;
        self.zeroClipboard.setMoviePath('assets/ZeroClipboard.swf');
        self.clip = new ZeroClipboard.Client();
        self.clip.addEventListener('mousedown',function() {
            self.clip.setText(self.embedTxt.text());
        });
        self.clip.addEventListener('complete',function(client,text) {
            alert('copied: ' + text);
        });
        self.clip.glue(this);
        self.clip.hide();
    });
    $(this.copy2).each(function loopingItems()
    {
        self.zeroClipboard = ZeroClipboard;
        self.zeroClipboard.setMoviePath('assets/ZeroClipboard.swf');
        self.clip2 = new ZeroClipboard.Client();
        self.clip2.addEventListener('mousedown',function() {
            self.clip2.setText(self.embedTxt2.text());
        });
        self.clip2.addEventListener('complete',function(client,text) {
            alert('copied: ' + text);
        });
        self.clip2.glue(this);
        self.clip2.hide();
    });
};
Video.fn.createEmbedWindowContent = function()
{
    var self=this;
    $(this.embedWindow).append('<p class="vpl-embedTitle vpl-themeColorText vpl-titles">' + self.options.embedWindowTitle1 + '</p>');
    $(this.embedWindow).append('<p class="vpl-embedTitle2 vpl-themeColorText vpl-titles">' + self.options.embedWindowTitle2 + '</p>');

    this.embedTxt = $("<p />")
        .addClass('vpl-embedText')
        .addClass("vpl-embedText"+" "+this.options.instanceTheme);
    this.embedWindow.append(this.embedTxt);

    this.copy = $("<div />")
        .attr("title", "Copy to clipboard")
        .attr('id', 'vpl-copy')
        .addClass('copyBtn')
        .addClass(this.options.instanceTheme);
    this.embedWindow.append(this.copy);
    $(this.embedWindow).find("#vpl-copy").append('<p id="vpl-copyInside" class="vpl-copyInside'+" "+this.options.instanceTheme+'">' + "Copy" + '</p>');

    $(this.embedWindow).append('<p class="vpl-embedTitle3 vpl-themeColorText vpl-titles">' + self.options.embedWindowTitle3 + '</p>');

    this.embedTxt2 = $("<p />")
        .addClass('vpl-embedText2')
        .addClass('vpl-embedText'+" "+this.options.instanceTheme);
    this.embedWindow.append(this.embedTxt2);

    this.copy2 = $("<div />")
        .attr("title", "Copy to clipboard")
        .attr('id', 'vpl-copy2')
        .addClass('copyBtn')
		.addClass(this.options.instanceTheme);
    this.embedWindow.append(this.copy2);
    $(this.embedWindow).find("#vpl-copy2").append('<p id="vpl-copyInside" class="vpl-copyInside'+" "+this.options.instanceTheme+'">' + "Copy" + '</p>');

	var s = this.options.embedCodeSrc;
	var w = this.options.embedCodeW;
	var h = this.options.embedCodeH;

	$(this.embedWindow).find(".vpl-embedText").text("<iframe src='"+s+"' width='"+w+"' height='"+h+"' frameborder=0 webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>");
    $(this.embedWindow).find(".vpl-embedText2").text(""+this.options.embedShareLink+"");
};
Video.fn.ready = function(callback)
{
  this.readyList.push(callback);  
  if (this.loaded)
      callback.call(this);
};

Video.fn.load = function(srcs)
{
  var self = this;
  if (srcs)
    this.sources = srcs;
  
  if (typeof this.sources == "string")
    this.sources = {src:this.sources};
  
  if (!$.isArray(this.sources))
    this.sources = [this.sources];
    
  this.ready(function()
  {
    this.change("loading");
	if(self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
	  {
		  this.video.loadSources(this.sources);
	  }      
  });
};
Video.fn.closeAD = function()
{
    var self=this;

	clearInterval(self.myInterval);

    self.videoPlayingAD=true;
    self.togglePlayAD();

    self._playlist.videoAdPlayed=true;

    self.resetPlayerAD();
    // self.elementAD.width(0);
    // self.elementAD.height(0);
    self.elementAD.css({zIndex:1});
	self.videoElementAD.empty();
    self.videoAdBoxInside.hide();
	self.removeListenerProgressAD();
	if(self.options.allowSkipAd)
	{
		self.skipAdBox.hide();
		self.skipAdCount.hide();
	}
    self.fsEnterADBox.hide();
    self.toggleAdPlayBox.hide();
    self.progressADBg.hide();
	if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
	{
		self.ytWrapper.css({visibility:"visible"});
		self.hideVideoElements();
		if(self.youtubePlayer!= undefined)
			this.youtubePlayer.playVideo();
	}
	else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
	{
		self.showVideoElements();
		self.togglePlay();
		self.video.play();
	}
	else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo" || self.options.videoType=="Vimeo")
	{
		self.hideVideoElements();
		if(self._playlist.vimeoPlayer!= undefined)
			self._playlist.vimeoPlayer.api('play');
		else
			self._playlist.playVimeo(self._playlist.videoid);
	}    
    
    //self.exitToOriginalSize();
};
Video.fn.openAD = function()
{
    var self=this;

    self.showVideoElements();
    self.progressADBg.show();
    self.elementAD.css({zIndex:555559});
	self.ytWrapper.css({visibility:"hidden"});
    self.videoAdBoxInside.show();
	if(self.options.allowSkipAd)
	{
		self.skipBoxOn = true;
		self.toggleSkipAdBox();
		self.skipCountOn=false;
		self.toggleSkipAdCount();
	}
	
    self.fsEnterADBox.show();
    
    if(!self.realFullscreenActive)
    self.resizeAll();

	if(this.isMobile.any())
	{
		if(!self.ADTriggered)
		{
			self.toggleAdPlayBox.show();
			self.videoPlayingAD=true;
			self.togglePlayAD();
		}
	}
	else
		self.toggleAdPlayBox.hide();	
	
	if(this.options.allowSkipAd)
	{
		this.setSkipTimer();
		$(".vpl-skipAdCountTitle").text(this.options.skipAdText + " " + self.counter + " s");
		this.i.src = self._playlist.videos_array[self._playlist.videoid].thumbnail_image;
	}
};
Video.fn.loadAD = function(srcs, active)
{
	this.preloaderAD.stop().animate({opacity:1},0,function(){$(this).show()});
    if (srcs)
        this.sourcesAD = srcs;

    if (typeof this.sourcesAD == "string")
        this.sourcesAD = {src:this.sourcesAD};

    if (!$.isArray(this.sourcesAD))
        this.sourcesAD = [this.sourcesAD];

    this.ready(function()
    {
        this.change("loading");
        this.videoAD.loadSources(this.sourcesAD);
    });
	
	switch(active){
		
		case "prerollActive":
			this.prerollActive = true;
			this.midrollActive = false;
			this.postrollActive = false;
		break;
		case "midrollActive":
			this.prerollActive = false;
			this.midrollActive = true;
			this.postrollActive = false;
		
		break;
		case "postrollActive":
			this.prerollActive = false;
			this.midrollActive = false;
			this.postrollActive = true;
		break;
	}
};
Video.fn.exitToOriginalSize = function(){
    if(THREEx.FullScreen.available())
    {
        if(THREEx.FullScreen.activated())
        {
           THREEx.FullScreen.cancel();
        }
        else if (this.inFullScreen)
        {
           this.fullScreen(!this.inFullScreen);
        }
    }
    else if(!THREEx.FullScreen.available())
    {
//        this.fullScreen(!this.inFullScreen);
    }
    this.elementAD.css({zIndex:455555});
}
Video.fn.play = function()
{
	var self = this;
	this.playButtonScreen.hide();
    this.playBtn.removeClass("fa-play").addClass("fa-pause");
	
	if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
    self.video.play();
	else if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
	self.video.pause();

	//not global ads
    if(self._playlist.videos_array[self._playlist.videoid].prerollAD=="yes" && self.videoAdStarted==false &&!self.options.showGlobalPrerollAds)
    {
        self.video.pause();
        if(!self.videoAdStarted && self._playlist.videos_array[self._playlist.videoid].prerollAD){
            if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
            {
                self.canPlay = true;
                self.video_pathAD = self._playlist.videos_array[self._playlist.videoid].preroll_mp4;
            }
            self.loadAD(self.video_pathAD, "prerollActive");
            self.openAD();
        }
        self.videoAdStarted=true;
    }
	//global ads
	if(this.options.showGlobalPrerollAds && self.videoAdStarted==false){
		self.video.pause();
		if(!self.videoAdStarted && self.options.showGlobalPrerollAds){
			if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
			{
				self.canPlay = true;
				self.video_pathAD = this.globalPrerollAds_arr[this.getGlobalPrerollRandomNumber()];
			}
			self.loadAD(self.video_pathAD);
			self.openAD();
		}
        self.videoAdStarted=true;
	}
};
Video.fn.pause = function()
{
    var self = this;
	if(this._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || this.options.videoType=="HTML5 (self-hosted)")
	{
		if(this.html5STARTED || this.options.posterImg=="")
			if(!this.is_iOSVolumeButtonScreen)
				this.playButtonScreen.show();
		else
			this.playButtonScreen.hide();
	}
	else if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
	{
		if(self._playlist.youtubeSTARTED || this.options.posterImg=="")
		{
			this.playButtonScreen.show();
		}
		else
			this.playButtonScreen.hide();
	}
	
    this.playBtn.removeClass("fa-pause").addClass("fa-play");
    self.video.pause();
};
Video.fn.stop = function()
{
  this.seek(0);
  this.pause();
};
Video.fn.hideOverlay = function(){
    var self = this;
    if(self.overlay==undefined)
        return;

    self.overlay.hide();
	self.poster2Showing = false;
    self.playButtonPoster.hide();

	if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
	{
		self.youtubePlayer.playVideo();
		if(self.options.youtubeControls=="default controls")
			self.element.css("visibility","hidden");
	}
	else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
	{
		self.togglePlay();
	}
	else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo" || self.options.videoType=="Vimeo")
	{
		self.hideCustomControls();
		self.hideVideoElements();
		
		if(self._playlist.vimeoPlayer!= undefined)
			self._playlist.vimeoPlayer.api('play');
		else
			self._playlist.playVimeo(self._playlist.videoid);
	}    
};
Video.fn.togglePlay = function(){
	
  if (this.state == "vpl-playing")
  {
    this.pause();
	if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
	this.youtubePlayer.pauseVideo();
  }
  else
  {
    this.play();
	if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube")
	this.youtubePlayer.playVideo();
  }
};
Video.fn.toggleSkipAdBox = function()
{
    var self = this;

    if(this.skipBoxOn)
    {
        this.skipAdBox.stop().animate({
			right:-(this.skipAdBox.width()-2),
			bottom:28
			},200,function() {
            $(this).hide();
       });
       this.skipBoxOn=false;
    }
    else
    {
        this.skipAdBox.show();
		this.addListenerProgressAD();
        this.skipAdBox.stop().animate({
			right:10,
			bottom:28
		},500);
        this.skipBoxOn=true;
    }
};
Video.fn.toggleSkipAdCount = function()
{
    var self = this;

    if(this.skipCountOn)
    {
        this.skipAdCount.stop().animate({
			right:-(this.skipAdCount.width()-2),
			bottom:28
			},200,function() {
            $(this).hide();
       });
       this.skipCountOn=false;
    }
    else
    {
        this.skipAdCount.show();
        this.skipAdCount.stop().animate({
			right:10,
			bottom:28
		},500);
        this.skipCountOn=true;
    }
};
Video.fn.toggleInfoWindow = function()
{
    var self = this;

    if(this.infoOn)
    {
        this.infoWindow.stop().animate({
			top:-(this.infoWindow.height())
			},200,function() {
            $(this).hide();
       });
	   this.nowPlayingTitle.show();
       this.infoOn=false;
    }
    else
    {
        this.infoWindow.show();
        this.infoWindow.stop().animate({
			top:0
		},500);
		this.nowPlayingTitle.hide();
        this.infoOn=true;
    }
};
Video.fn.toggleLightBox = function()
{
    var self = this;
	
    if(this.lightBoxOn)
    {
        this.lightBoxOverlay.stop().animate({
			opacity:0
			},300,function() {
            $(this).hide();
		});
		this.lightBoxOn=false;
		
		this.pause();
		if(this.YTAPIReady)
			this.youtubePlayer.pauseVideo();
		
		if(this._playlist.vimeoPlayer)
			this._playlist.vimeoPlayer.api('pause');
	
		this.videoPlayingAD=true;
		this.togglePlayAD();
		
		//thumbnail play button zindex
		$('.vpl-lightBoxThumbnailWrap').each(function(i, obj) {
			$(this).find(".vpl-start-button").css({
				zIndex: 2147483647
			})
		});
    }
    else
    {
        this.lightBoxOverlay.show();
        this.lightBoxOverlay.stop().animate({
			opacity:1
		},300);
        this.lightBoxOn=true;
		
		if(this.options.lightBoxAutoplay){
			
			if(!this.lightBoxInitiated){//open lightbox and play video by id
				this.playVideoById(this._playlist.videoid);
				this.lightBoxInitiated = true;
			}
			else{//open lightbox and resume playing
				if(this._playlist.videos_array[this._playlist.videoid].prerollAD=="yes" || this.options.showGlobalPrerollAds){
					if(!this._playlist.videoAdPlayed){
						//play ad
						this.videoPlayingAD=false;
						this.togglePlayAD();
					}
					else{
						//dont play ad,play main video
						this.play();
						if(this.YTAPIReady)
							this.youtubePlayer.playVideo();
						if(this._playlist.vimeoPlayer)
							this._playlist.vimeoPlayer.api('play');
					}
				}
				else{
					this.play();
					if(this.YTAPIReady)
						this.youtubePlayer.playVideo();
					if(this._playlist.vimeoPlayer)
						this._playlist.vimeoPlayer.api('play');
				}
				
			}
			
		}
		//thumbnail play button zindex
		$('.vpl-lightBoxThumbnailWrap').each(function(i, obj) {
			$(this).find(".vpl-start-button").css({
				zIndex: 2147483646
			})
		});
    }
	this.resizeAll();
};
Video.fn.toggleQualityWindow = function(val)
{
    var self = this;
	
	var delay;
	if(val)
		delay = val
	else
		delay = 0
    if(this.qualityOn)
    {
        this.qualityWindow.stop().delay(delay).animate({
			top:200
			},200,function() {
            $(self.qualityWindow_mask).hide();
       });
       this.qualityOn=false;
    }
    else
    {
        this.qualityWindow_mask.show();
        this.qualityWindow.stop().delay(delay).animate({
			top: 0
		},500);
        this.qualityOn=true;
    }
};
Video.fn.togglePopup = function()
{
    if(this.adOn)
    {
        this.adImg.animate({opacity:0},0,function() {
            // Animation complete.
            $(this).hide();
        });
        this.adOn=false;
    }
    else if(!this.adOn)
    {
        this.adImg.show();
        this.adImg.animate({opacity:1},500);
        this.adOn=true;

    }
};
Video.fn.toggleShuffleBtn = function()
{
    var self = this;
    if(this.shuffleBtnEnabled)
    {
	   this.removeColorAccent($("#vpl-shuffleBtn"));
       this.shuffleBtnEnabled=false;
    }
    else
    {
        $(this.mainContainer).find(".fa-random").addClass("vpl-themeColorText");
        this.shuffleBtnEnabled=true;
		this.setColorAccent(this.options.colorAccent, $("#vpl-shuffleBtn"));
    }
};
Video.fn.toggleQualityBtn = function()
{
    var self = this;
    if(this.qualityBtnEnabled)
    {
	   this.removeColorAccent($("#vpl-qualityBtn"));
       this.qualityBtnEnabled=false;
    }
    else
    {
        $(this.mainContainer).find(".fa-cog").addClass("vpl-themeColorText");
        this.qualityBtnEnabled=true;
		this.setColorAccent(this.options.colorAccent, $("#vpl-qualityBtn"));
    }
};
Video.fn.toggleShareWindow = function()
{
    var self = this;

    if(this.shareOn)
    {
		this.shareOn=false;
        $(this.shareWindow).stop().animate({
			right:-(self.shareWindow.width())
		},300,function() {
            $(this).hide();
       });
    }
    else
    {
        this.shareWindow.show();
        $(this.shareWindow).stop().animate({
			right: self.screenBtnsWindow.width()
		},300);
		this.shareOn=true;
    }
};
Video.fn.togglePlayAD = function()
{
    var self = this;

    if(this.videoPlayingAD)
    {
        this.videoAD.pause();
        this.videoPlayingAD=false;
        this.toggleAdPlayBox.show();
    }
    else
    {
        this.videoAD.play();
        this.videoPlayingAD=true;
        this.toggleAdPlayBox.hide();
    }
};
Video.fn.toggleEmbedWindow = function()
{
    var self = this;
    if(this.embedOn)
    {
        $(this.embedWindow).stop().animate({
				top:-(this.embedWindow.height())
			},200,function() {
            $(this).hide();
            self.clip.hide();
            self.clip2.hide();
			self.clip.destroy();
			self.clip2.destroy();
        });
        this.embedOn=false;
    }
    else
    {
        $(this.embedWindow).show();
        $(this.embedWindow).stop().animate({top:0},500,function(){
			//complete
			self.attachZeroClipboard();
			self.clip.show();
			self.clip2.show();
		});
        this.embedOn=true;
    }
};
Video.fn.fullScreen = function(state)
{
    var self = this;
    if(state)
    {
        this.element.addClass("vpl-fullScreen");
        this.elementAD.addClass("vpl-fullScreen");
        $(this.mainContainer).find(".fa-expand").removeClass("fa-expand").addClass("fa-compress");
        $(this.fsEnterADBox). find(".fa-expandAD").removeClass("fa-expandAD").addClass("fa-compressAD");
		
        this._playlist.hidePlaylist();

        self.element.width(window.innerWidth);
        self.element.height(window.innerHeight);
        self.elementAD.width(window.innerWidth);
        self.elementAD.height(window.innerHeight);
		self.mainContainer.width(window.innerWidth);
        self.mainContainer.height(window.innerHeight);
		self.mainContainer.css("position","fixed");
		self.mainContainer.css("left",0);
		self.mainContainer.css("top",0);
				
		self.mainContainer.parent().css("zIndex",999999);
		
		if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
			self.element.css({zIndex:555558 });
		if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
			self.element.css({zIndex:555558 });
		else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo" || self.options.videoType=="Vimeo")
			self.element.css({zIndex:555556});

        if(self._playlist.videos_array[self._playlist.videoid].prerollAD=="yes" || self.options.showGlobalPrerollAds){
            if(!self._playlist.videoAdPlayed){
                self.elementAD.css({
                    zIndex:555559
                });
            }
            else{
                    self.elementAD.css({
                        zIndex:555557
                    });
            }
        }
    }
    else
    {
        this._playlist.showPlaylist();
        this.element.removeClass("vpl-fullScreen");
		self.mainContainer.css("position","absolute");
		self.mainContainer.parent().css("zIndex",1);
        this.elementAD.removeClass("vpl-fullScreen");
        $(this.mainContainer).find(".fa-compress").removeClass("fa-compress").addClass("fa-expand");
        $(this.fsEnterADBox). find(".fa-compressAD").removeClass("fa-compressAD").addClass("fa-expandAD");

        if(this.stretching)
        {
            //back to stretched player
            this.stretching=false;
            this.toggleStretch();
        }

		if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
			self.element.css({zIndex:455558 });
		else
			self.element.css({zIndex:455556});        
			
        if(self._playlist.videos_array[self._playlist.videoid].prerollAD=="yes" || self.options.showGlobalPrerollAds){
            if(!self._playlist.videoAdPlayed){
                self.elementAD.css({
                    zIndex:455559
                });
            }
            else{
                    self.elementAD.css({
                        zIndex:455557
                    });
            }
        }
		if(self.options.playerLayout == "fitToContainer" || self.options.playerLayout == "fitToBrowser")
		{
			self.mainContainer.width("100%");
			self.mainContainer.height("100%");
		}
		else if(self.options.playerLayout == "fixedSize"){
			self.mainContainer.width(self.options.videoPlayerWidth);
			self.mainContainer.height(self.options.videoPlayerHeight);
		}
		self.mainContainer.css("left","");
		self.mainContainer.css("top","");
        self.resizeAll();
    }
    this.resizeVideoTrack();
    this.positionOverScreenButtons(state);
    this.positionLogo();
    this.positionPopup();
    // this.positionPoster();
    this.resizeBars();
  if (typeof state == "undefined") state = true;
  this.inFullScreen = state;
};
Video.fn.toggleFullScreen = function()
{
    var self = this;

    if(THREEx.FullScreen.available())
    {
        if(THREEx.FullScreen.activated())
        {
            if(this.options.fullscreen=="Fullscreen native")
                THREEx.FullScreen.cancel();
            if(this.options.fullscreen=="Fullscreen browser")
                this.fullScreen(!this.inFullScreen);
			if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
				self.element.css({zIndex:455558 });
			else
				self.element.css({zIndex:455556});            
				
            if(self._playlist.videos_array[self._playlist.videoid].prerollAD=="yes" || self.options.showGlobalPrerollAds){
                if(!self._playlist.videoAdPlayed ){
                    self.elementAD.css({
                        zIndex:455559
                    });
                }
                else{
                        self.elementAD.css({
                            zIndex:455557
                        });
                }
            }
			self.mainContainer.css("zIndex",999999);
			// $(".vpl-mainContainer").appendTo("#vpl");
			// $(".vpl-mainContainer").appendTo(".vpl");
        }
        else
        {
            if(this.options.fullscreen=="Fullscreen native")
            {    
			
				THREEx.FullScreen.request();
				self.mainContainer.parent().css("zIndex",999999);
				self.mainContainer.css("zIndex",2147483647);
				
				// $(".vpl-mainContainer").appendTo("body");
				
				if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
					self.element.css({zIndex:555558 });
				if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
					self.element.css({zIndex:555558 });
				else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo" || self.options.videoType=="Vimeo")
					self.element.css({zIndex:555556});
				
                if(self._playlist.videos_array[self._playlist.videoid].prerollAD=="yes" || self.options.showGlobalPrerollAds){
					if(!self.videoAdStarted)
							return;
						
                    if(!self._playlist.videoAdPlayed){
                        self.elementAD.css({
                            zIndex:555559
                        });
                    }
                    else{
                            self.elementAD.css({
                                zIndex:555557
                           });
                    }
                }
            }
			if(this.options.fullscreen=="Fullscreen browser")
                this.fullScreen(!this.inFullScreen);
        }
    }
    else if(!THREEx.FullScreen.available())
    {
        this.fullScreen(!this.inFullScreen);
    }
};

Video.fn.seek = function(offset)
{
  this.video.setCurrentTime(offset);
};

Video.fn.setVolume = function(num)
{
  this.video.setVolume(num);
};

Video.fn.getVolume = function()
{
  return this.video.getVolume();
};

Video.fn.mute = function(state)
{
  if (typeof state == "undefined") state = true;
  this.setVolume(state ? 1 : 0);
};

Video.fn.remove = function()
{
  this.element.remove();
};

Video.fn.bind = function()
{
  this.videoElement.bind.apply(this.videoElement, arguments);
};

Video.fn.one = function()
{
  this.videoElement.one.apply(this.videoElement, arguments);
};

Video.fn.trigger = function()
{
  this.videoElement.trigger.apply(this.videoElement, arguments);
};
// Proxy jQuery events
var events = [
               "click",
               "dblclick",
               "onerror",
               "onloadeddata",
               "oncanplay",
               "ondurationchange",
               "ontimeupdate",
               "onprogress",
               "onpause",
               "onplay",
               "onended",
               "onvolumechange"
             ];

for (var i=0; i < events.length; i++)
{
  (function()
  {
    var functName = events[i];
    var eventName = functName.replace(/^(on)/, "");
    Video.fn[functName] = function()
    {
      var args = $.makeArray(arguments);
      args.unshift(eventName);
      this.bind.apply(this, args);
    };
  }
  )();
}
// Private methods
Video.fn.triggerReady = function()
{
	// for (var i in this.readyList)
	// {
	// this.readyList[i].call(this);
	// }
	this.loaded = true;
  
	//multiple videos:
	$('video').each(function () {
		enableInlineVideo(this);
	});
};
Video.fn.setupElement = function()
{
    var self=this;
    this.mainContainer=$("<div />");
    this.mainContainer.addClass("vpl-mainContainer");
    if(this.options.playerLayout == "fitToContainer" || this.options.playerLayout == "fitToBrowser"){
        this.mainContainer.css({
            width:"100%",
            height:"100%",
            position:"absolute",
            background:"#000000",
			zIndex:999999
        });
    }
    else if(this.options.playerLayout == "fixedSize"){
        this.mainContainer.css({
            width:this.options.videoPlayerWidth,
            height:this.options.videoPlayerHeight,
            position:"absolute",
            background:"#000000",
			zIndex:999999
        });
    }
    switch( this.options.videoPlayerShadow ) {
        case 'effect1':
            this.mainContainer.addClass("vpl-effect1");
            break;
        case 'effect2':
            this.mainContainer.addClass("vpl-effect2");
            break;
        case 'effect3':
            this.mainContainer.addClass("vpl-effect3");
            break;
        case 'effect4':
            this.mainContainer.addClass("vpl-effect4");
            break;
        case 'effect5':
            this.mainContainer.addClass("vpl-effect5");
            break;
        case 'effect6':
            this.mainContainer.addClass("vpl-effect6");
            break;
        case 'off':
            break;
    }
    this.parent.append(this.mainContainer);

	if(this.options.lightBox){
		this.mainContainerBG=$("<div />");
		this.mainContainerBG.addClass("vpl-mainContainerBG");
		this.mainContainer.append(this.mainContainerBG)
	}
	
	this.element = $("<div />");
	this.element.addClass("vpl-video-player");
	this.mainContainer.append(this.element);
  
	this.ytWrapper = $('<div></div>');
	this.ytWrapper.addClass('vpl-youtube');
	this.element.append(this.ytWrapper);

	this.ytPlayer = $('<div></div>');
	this.ytPlayer.attr("id", self.options.instanceName + "youtube");
	this.ytWrapper.append(this.ytPlayer);
	
	this.imageWrapper = $('<div></div>');
	this.imageWrapper.addClass('vpl-imageWrapper');
	this.element.append(this.imageWrapper);
	
	this.imageDisplayed = document.createElement('img');
    this.imageWrapper.append(this.imageDisplayed);
	$('.vpl-imageWrapper img').attr('id','vpl-imageDisplayed');
};
Video.fn.setupElementAD = function()
{
    this.elementAD = $("<div />");
    this.elementAD.addClass("vpl-ads");
    this.mainContainer.append(this.elementAD);
};
Video.fn.idle = function(e, toggle){
    var self=this;
  if (toggle)
  {
    if (this.state == "vpl-playing")
    {
		if(!this.options.showAllControls)
			this.controls.hide();
		this.controls.stop().animate({bottom:-50} , 300);
		self.progressIdleTrack.stop().delay(800).animate({bottom:0} , 300);
        this.screenBtnsWindow.stop().animate({right:-44} , 300); 
        this.logoImg.stop().animate({
			opacity:0
		} , 300);
		
        self.nowPlayingTitle.stop().animate({
			left:-(self.nowPlayingTitleW)
		} , 300);
		self.shareOn=true;
		self.toggleShareWindow();
		self.qualityOn=true;
		self.toggleQualityWindow();
		self.qualityBtnEnabled=true;
		self.toggleQualityBtn();
		$(self.toolTip).stop().animate({opacity:0},50,function(){
			self.toolTip.hide()
		});
		self.invisibleWrapper.show();
    }
  }
  else
  {
	  this.progressIdleTrack.stop().animate({bottom:-6},100,function(){
		  // Animation complete.
		  if(!self.options.showAllControls)
			self.controls.hide();
		  self.controls.stop().animate({bottom:0} , 300);
	  });
	  this.screenBtnsWindow.stop().animate({right:0} , 400);
      this.logoImg.stop().animate({
		opacity:1
	  } , 400);
      self.nowPlayingTitle.stop().animate({
		left:0
	  } , 400);
	  self.invisibleWrapper.hide();
  }
};
Video.fn.change = function(state)
{
  this.state = state;
    if(this.element){
        this.element.attr("data-state", this.state);
        this.element.trigger("state.videoPlayer", this.state);
    }
}
Video.fn.setupHTML5Video = function()
  {
      if(this.element)
      {
          this.element.append(this.videoElement);
		  /*this.canvas = $('<canvas id="canvas" width="750px" height="540px" style="display:block;"></canvas>').appendTo(this.element)
		  this.screenShotsContainer = $('<div id="screenShots"></div>').appendTo(this.element)*/
      }
      this.video = this.videoElement[0];

      if(this.element)
      {
          this.element.width(this.playerWidth);
          this.element.height(this.playerHeight);
      }
      var self = this;

      this.video.loadSources = function(srcs)
      {
		  
        self.videoElement.empty();
		
        for (var i in srcs)
        {
          // var srcEl = $("<source />");
          // srcEl.attr(srcs[i]);
          // self.videoElement.append(srcEl);
		  if(srcs[i].src.indexOf('m3u8') != -1){
			if(Hls.isSupported()) {
				// var video = document.getElementById('video');
				var hls = new Hls();
				// hls.loadSource('https://video-dev.github.io/streams/x36xhzz/x36xhzz.m3u8');
				hls.loadSource(srcs[i].src);
				hls.attachMedia(self.video);
				hls.on(Hls.Events.MANIFEST_PARSED,function() {
					// video.play();
					// self.videoElement[0].play();
				});
			}
		  }
		  else
			self.videoElement.attr('src', srcs[i].src)
				
		  // self.video.pause();
		  // self.video.setCurrentTime(0);
		  // self.videoElement.attr('src', ' ');
          // self.videoElement.attr(srcs[i]);
        }
        self.video.load();

      };

      this.video.getStartTime = function()
      {
          return(this.startTime || 0);
      };
      this.video.getEndTime = function()
      {
        if (this.duration == Infinity && this.buffered)
        {
          return(this.buffered.end(this.buffered.length-1));
        }
        else
        {
          return((this.startTime || 0) + this.duration);
        }
      };

      this.video.getCurrentTime = function(){
        try
        {
          return this.currentTime;
        }
        catch(e)
        {
          return 0;
        }
      };


      var self = this;

      this.video.setCurrentTime = function(val)
      {
          this.currentTime = val;
      };
      this.video.getVolume = function()
      {
          return this.volume;
      };
      this.video.setVolume = function(val)
      {
		  if(val>1)
			  val = 1;
          if(self.options.showAllControls)
			this.volume = val;
		  else
			this.volume = 1;
      };

      this.videoElement.dblclick($.proxy(function()
      {
        this.toggleFullScreen();
      }, this));
      this.videoElement.bind(this.CLICK_EV, $.proxy(function()
      {
        this.togglePlay();
		
      }, this));

      this.triggerReady();
	  
	  
		
};
Video.fn.setupHTML5VideoAD = function()
{
    if(this.elementAD)
    {
        this.elementAD.append(this.videoElementAD);
    }
    this.videoAD = this.videoElementAD[0];

    if(this.elementAD)
    {
        this.elementAD.width(0);
        this.elementAD.height(0);
    }
    var self = this;
    this.videoAD.loadSources = function(srcs)
    {
        self.videoElementAD.empty();
        for (var i in srcs)
        {
            // var srcEl = $("<source />");
            // srcEl.attr(srcs[i]);
            // self.videoElementAD.append(srcEl);
			if(srcs[i].src.indexOf('m3u8') != -1){
				if(Hls.isSupported()) {
					var hls = new Hls();
					hls.loadSource(srcs[i].src);
					hls.attachMedia(self.videoAD);
					hls.on(Hls.Events.MANIFEST_PARSED,function() {
						// self.videoElement[0].play();
					});
				}
			  }
			  else
				self.videoElementAD.attr('src', srcs[i].src)
        }
        self.videoAD.load();
		if(self.isMobile.any())
			self.videoPlayingAD=true;
		else
			self.videoPlayingAD=false;
        self.togglePlayAD();
    };

    this.videoAD.getStartTime = function()
    {
        return(this.startTime || 0);
    };
    this.videoAD.getEndTime = function()
    {
        if(isNaN(this.duration))
        {
            /*self.timeTotal.text("--:--");*/
        }
        else
        {
            if (this.duration == Infinity && this.buffered)
            {
                return(this.buffered.end(this.buffered.length-1));
            }
            else
            {
                return((this.startTime || 0) + this.duration);
            }
        }

    };
    this.videoAD.getCurrentTime = function(){
        try
        {
            return this.currentTime;
        }
        catch(e)
        {
            return 0;
        }
    };
    this.videoAD.setCurrentTime = function(val)
    {
        this.currentTime = val;
    }
    this.videoAD.getVolume = function()
    {
        return this.volume;
    };
    this.videoAD.setVolume = function(val)
    {
        this.volume = val;
    };
    this.videoElementAD.dblclick($.proxy(function()
    {
        this.toggleFullScreen();
    }, this));
    this.triggerReady();
    this.videoElementAD.bind(this.CLICK_EV, $.proxy(function()
    {
		//global ads
		if(this.options.showGlobalPrerollAds){
			if((this.options.globalPrerollAdsGotoLink != "") && (this.options.globalPrerollAdsGotoLink != "globalPrerollAdsGotoLink")){
				window.open(this.options.globalPrerollAdsGotoLink);
				this.videoPlayingAD=true;
				this.togglePlayAD();
			}
		}
		else{
			if((this._playlist.videos_array[this._playlist.videoid].prerollGotoLink !="") &&  (this._playlist.videos_array[this._playlist.videoid].prerollGotoLink !="prerollGotoLink") && (this._playlist.videos_array[this._playlist.videoid].prerollAD == "yes"))
			{
				if(this.prerollActive)
					window.open(this._playlist.videos_array[this._playlist.videoid].prerollGotoLink);
				this.videoPlayingAD=true;
				this.togglePlayAD();
			}
			if((this._playlist.videos_array[this._playlist.videoid].midrollGotoLink !="") &&  (this._playlist.videos_array[this._playlist.videoid].midrollGotoLink !="midrollGotoLink") && (this._playlist.videos_array[this._playlist.videoid].midrollAD == "yes"))
			{
				if(this.midrollActive)
					window.open(this._playlist.videos_array[this._playlist.videoid].midrollGotoLink);
				this.videoPlayingAD=true;
				this.togglePlayAD();
			}
			if((this._playlist.videos_array[this._playlist.videoid].postrollGotoLink !="") &&  (this._playlist.videos_array[this._playlist.videoid].postrollGotoLink !="postrollGotoLink") && (this._playlist.videos_array[this._playlist.videoid].postrollAD == "yes"))
			{
				if(this.postrollActive)
					window.open(this._playlist.videos_array[this._playlist.videoid].postrollGotoLink);
				this.videoPlayingAD=true;
				this.togglePlayAD();
			}
		}
    }, this));
};
Video.fn.setupButtonsOnScreen = function(){

    var self = this;
    this.screenBtnsWindow = $("<div></div>");
    this.screenBtnsWindow.addClass("vpl-toggle-playlist");
    if(this.element)
    this.element.append(this.screenBtnsWindow);
	if(!this.options.showAllControls)
		this.screenBtnsWindow.hide();
    this.playlistBtn = $("<div />")
        .addClass("vpl-playlist-button")
		.addClass("vpl-playerElement")
        .addClass("vpl-btnOverScreen")
    if(this.element)
        this.screenBtnsWindow.append(this.playlistBtn);
    
    this.playlistBtnIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen") 
        .addClass("vpl-icon-hover-screen"+" "+this.options.instanceTheme) 
        .addClass("fa-indent");
    this.playlistBtn.append(this.playlistBtnIcon);

    this.shareBtn = $("<div />")
        .addClass("vpl-shareBtn")
		.addClass("vpl-playerElement")
        .addClass("vpl-btnOverScreen")
    if(this.element)
        // this.screenBtnsWindow.append(this.shareBtn);
    
    this.shareBtnIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen")
        .addClass("vpl-icon-hover-screen"+" "+this.options.instanceTheme)
		.addClass("vpl-controlsColor")
		.addClass("fa-share-square-o")
    this.shareBtn.append(this.shareBtnIcon);

    this.embedBtn = $("<div />")
        .addClass("vpl-embedBtn")
		.addClass("vpl-playerElement")
        .addClass("vpl-btnOverScreen")
    if(this.element){
        // this.screenBtnsWindow.append(this.embedBtn);
    }
    this.embedBtnIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen")
        .addClass("vpl-icon-hover-screen"+" "+this.options.instanceTheme)
		.addClass("fa-chain");
    this.embedBtn.append(this.embedBtnIcon);

    this.infoBtn = $("<div />")
        .addClass("vpl-infoBtn")
		.addClass("vpl-playerElement")
        .addClass("vpl-btnOverScreen")
		
	this.playlistBtn.addClass("vpl-bg"+" "+this.options.instanceTheme)
	this.shareBtn.addClass("vpl-bg"+" "+this.options.instanceTheme)
	this.embedBtn.addClass("vpl-bg"+" "+this.options.instanceTheme)
	this.infoBtn.addClass("vpl-bg"+" "+this.options.instanceTheme)

		
    if(this.element){
        // this.screenBtnsWindow.append(this.infoBtn);
    }
    this.infoBtnIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen")
        .addClass("vpl-icon-hover-screen"+" "+this.options.instanceTheme)
        .addClass("fa-info");
    this.infoBtn.append(this.infoBtnIcon);
	
	

    this.shareWindow = $("<div></div>");

    this.shareBtn.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleShareWindow();
    }, this));

    this.facebookBtn = $("<div />")
        .addClass("vpl-facebookBtn")
        .addClass("vpl-playerElement")
        .addClass("vpl-socialBtn")
		.addClass("vpl-bg");
    if(this.element){
        this.shareWindow.append(this.facebookBtn);
    }
    this.facebookBtnIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen")
        .addClass("fa-facebook");
    this.facebookBtn.append(this.facebookBtnIcon);

    this.twitterBtn = $("<div />")
        .addClass("vpl-twitterBtn")
		.addClass("vpl-playerElement")
        .addClass("vpl-socialBtn")
		.addClass("vpl-bg");
    if(this.element){
        this.shareWindow.append(this.twitterBtn);
    }
    this.twitterBtnIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen")
        .addClass("fa-twitter");
    this.twitterBtn.append(this.twitterBtnIcon);

    this.mailBtn = $("<div />")
        .addClass("vpl-mailBtn")
		.addClass("vpl-playerElement")
        .addClass("vpl-socialBtn")
		.addClass("vpl-bg");
    if(this.element){
        this.shareWindow.append(this.mailBtn);
    }
    this.mailBtnIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen")
        .addClass("fa-google-plus");
    this.mailBtn.append(this.mailBtnIcon);

	var m = 5;
	this.shareWindow.css({
		right:-(this.shareWindow.width()),
		top:self.shareBtn.position().top + m
	}).hide();
	
    this.facebookBtn.bind(this.CLICK_EV,$.proxy(function(){
        self.pause();
		if(self.YTAPIReady)
			self.youtubePlayer.pauseVideo();

		var left  = ($(window).width()/2)-(600/2),
			top   = ($(window).height()/2)-(400/2),
			popup = window.open ("https://www.facebook.com/dialog/feed?app_id=787376644686729"
			+"&display=popup"
			+"&name="+self.options.facebookShareName
			+"&link="+self.options.facebookShareLink
			+"&redirect_uri=https://facebook.com"
			+"&description="+self.options.facebookShareDescription
			+"&picture="+self.options.facebookSharePicture
			, "popup", "width=600, height=400, top="+top+", left="+left);
		if (window.focus)
		{
		  popup.focus();
		}
    }, this));
	
    this.twitterBtn.bind(this.CLICK_EV,$.proxy(function(){
        self.pause();
		if(self.YTAPIReady)
			self.youtubePlayer.pauseVideo();
		
		var left  = ($(window).width()/2)-(600/2),
			top   = ($(window).height()/2)-(400/2),
			popup = window.open ("https://twitter.com/intent/tweet"
			+"?text="+self.options.twitterText
			+"&url="+self.options.twitterLink
			+"&hashtags="+self.options.twitterHashtags
			+"&via="+self.options.twitterVia
			, "popup", "width=600, height=400, top="+top+", left="+left);
		if (window.focus)
		{
		  popup.focus();
		}
    }, this));
    this.mailBtn.bind(this.CLICK_EV,$.proxy(function(){
        self.pause();
		if(self.YTAPIReady)
			self.youtubePlayer.pauseVideo();
		
		var left  = ($(window).width()/2)-(600/2),
			top   = ($(window).height()/2)-(400/2),
			popup = window.open ("https://plus.google.com/share"
			+"?url="+self.options.googlePlus
			, "popup", "width=600, height=400, top="+top+", left="+left);
		if (window.focus)
		{
		  popup.focus();
		}
    }, this));
    $(".vpl-shareBtn, .vpl-embedBtn, .vpl-playlist-button, .vpl-infoBtn, .vpl-infoBtn, .vpl-facebookBtn, .vpl-twitterBtn, .vpl-mailBtn").mouseover(function(){
        $(this).find(".vpl-icon-hover-screen").removeClass("vpl-icon-hover-screen").addClass("vpl-icon-hover");
    });
    $(".vpl-shareBtn, .vpl-embedBtn, .vpl-playlist-button, .vpl-infoBtn, .vpl-infoBtn, .vpl-facebookBtn, .vpl-twitterBtn, .vpl-mailBtn").mouseout(function(){
        $(this).find(".vpl-icon-hover").removeClass("vpl-icon-hover").addClass("vpl-icon-hover-screen");
    });
	
	$(".vpl-btnOverScreen").mouseover(function(){
        $(this).css("background",self.options.colorAccent);
    });
    $(".vpl-btnOverScreen").mouseout(function(){
        $(this).css("background","");
    });
    if(self.options.shareShow=="No")
        this.shareBtn.hide();
    if(self.options.embedShow=="No")
        this.embedBtn.hide();
    if(self.options.infoShow=="No")
        this.infoBtn.hide();
    
    if(self.options.facebookShow=="No")
        this.facebookBtn.hide();
    if(self.options.twitterShow=="No")
        this.twitterBtn.hide();
    if(self.options.mailShow=="No")
        this.mailBtn.hide();

    buttonsMargin = 5;

    this.positionOverScreenButtons();

    this.playlistBtn.bind(this.CLICK_EV, function(){
        self.toggleStretch();
        self.resizeAll();
    });
};
Video.fn.toggleStretch = function(){
    var self=this;
    if(this.stretching)
    {
        self.shrinkPlayer();
        this.stretching = false;
		this.playlistBtnIcon.removeClass("fa-dedent").addClass("fa-indent");
    }
    else
    {
        self.stretchPlayer();
        this.stretching = true;
		this.playlistBtnIcon.removeClass("fa-indent").addClass("fa-dedent");
    }
    this.resizeVideoTrack();
    this.positionOverScreenButtons();
    this.positionLogo();
    this.positionPopup();
    this.resizeBars();
    this.resizeAll();
};
Video.fn.stretchPlayer = function(){
    this.element.width(this.options.videoPlayerWidth);
};
Video.fn.shrinkPlayer = function(){
    this.element.width(this.playerWidth);
};
Video.fn.positionOverScreenButtons = function(state){
    if(this.element){

		if(document.webkitIsFullScreen || document.fullscreenElement || document.mozFullScreen || state)
		{
			this.playlistBtn.hide();
		}
		else
		{
			if(this.options.playlist=="Right playlist" || this.options.playlist=="Bottom playlist")
				this.playlistBtn.show();
			else
				this.playlistBtn.hide();
		}
    }
};
Video.fn.hideControls = function(){
    var self = this;

    $(this.element).hover(function(){
		if(!self.options.showAllControls)
			self.controls.hide();
		self.controls.stop().animate({bottom:0} , 300);
		self.progressIdleTrack.stop().animate({bottom:-6} , 100);
		self.screenBtnsWindow.stop().animate({right:0} , 300);
		self.logoImg.stop().animate({
			opacity:1
		} , 300);
		self.nowPlayingTitle.stop().animate({
			left:0
		} , 300);
    },function(){
		if(!self.options.showAllControls)
			self.controls.hide();
		self.controls.stop().animate({bottom:-50} , 300);
		self.progressIdleTrack.stop().delay(800).animate({bottom:0} , 300);
        self.screenBtnsWindow.stop().animate({right:-44} , 300); 
        self.logoImg.stop().animate({
			opacity:0
		} , 300);
        self.nowPlayingTitle.stop().animate({
			left:-(self.nowPlayingTitleW)
		} , 300);
    });
};
Video.fn.setupButtons = function(){
  var self = this;

    this.playBtn = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("vpl-icon-hover-screen")
        .addClass("fa-play")
        .addClass("vpl-playerElement")
        .addClass("vpl-themeColor")
		.attr("id", "vpl-playBtn")
	this.playBtnBg = $("<div />")
		.addClass("vpl-playBtnBg")
		.addClass("vpl-playerElement")
		.bind(self.CLICK_EV, function(){
            self.togglePlay();
        });
	this.controls.append(this.playBtnBg);
	this.playBtnBg.append(this.playBtn);

	this.rewindBtnWrapper = $("")
	this.controls.append(this.rewindBtnWrapper)
	
	this.qualityBtnWrapper = $("<div />")
		.addClass("vpl-qualityBtnWrapper")
		.addClass("vpl-playerElement")
		.bind(self.CLICK_EV, function(){
			self.toggleQualityBtn();
			self.toggleQualityWindow();
			$(this).children(":first").toggleClass("fa-rotate-90")
        })
		.hide();
	if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
		this.qualityBtnWrapper.show()
	this.controls.append(this.qualityBtnWrapper)
	
	this.qualityBtn = $("<span />")
        .attr("aria-hidden","true")
        .attr("id", "vpl-qualityBtn")
        .addClass("fa")
        .addClass("icon-general")
		.addClass("vpl-controlsColor"+" "+this.options.instanceTheme)
        .addClass("fa-cog")
    this.qualityBtnWrapper.append(this.qualityBtn);//Quality BTN
	
	this.HD_indicator = $("<div />")
		.addClass("vpl-HD_indicator")
		.addClass("icon-general")
		.addClass("vpl-quality-content-label")
		.text("HD")
		.hide();
	this.qualityBtnWrapper.append(this.HD_indicator)

	
	this.downloadBtnLink = $("<a />")
		.attr('href', this._playlist.videos_array[this._playlist.videoid].video_path_mp4)
		.attr('download', '')
		.hide()
	this.downloadBtnWrapper = $("<div />")
		.addClass("vpl-downloadBtnWrapper")
		.addClass("vpl-playerElement")
		.bind(self.CLICK_EV, function(){

        });
	this.downloadBtnLink.append(this.downloadBtnWrapper)	
	this.downloadBtn = $("<span />")
        .attr("aria-hidden","true")
        .attr("id", "vpl-downloadBtn")
        .addClass("fa")
        .addClass("icon-general")
		.addClass("vpl-controlsColor"+" "+this.options.instanceTheme)
        .addClass("fa-download")
    this.downloadBtnWrapper.append(this.downloadBtn);//Download BTN
	if((this._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || this.options.videoType=="HTML5 (self-hosted)")&&this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes")
		this.downloadBtnLink.show()
	// if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download =="yes")
		this.controls.append(this.downloadBtnLink)
	

	if(self.options.shuffle=="Yes"){
		this.shuffleBtnEnabled=false;
		this.toggleShuffleBtn();
	}
	else
		this.shuffleBtnEnabled=false;
		
	//PLAY BTN SCREEN
	this.playButtonScreen = $("<div />");
	this.playButtonScreen.addClass("vpl-start-button")
	  .attr("aria-hidden","true")
	  .addClass("fa")
	  .addClass("fa-play"+" "+this.options.instanceTheme)
	  .hide();
	this.playButtonScreen.bind(this.CLICK_EV,$.proxy(function()
	{
		// this.play();
		this.togglePlay();
	}, this))
	
	if(isMobile.iOS() && this.options.autoplay){
		//iOS VOLUME BTN SCREEN
		var is_iOSVolumeButtonScreen = true;
		
		this.iOSVolumeButtonScreen = $("<div />");
		this.iOSVolumeButtonScreen.addClass("vpl-iOSVolumeButtonScreen")
		  .attr("aria-hidden","true")
		  .addClass("fa")
		  .addClass("fa-iOSBtnScreen"+" "+this.options.instanceTheme).addClass("pulse")
		this.iOSVolumeButtonScreen.bind(this.CLICK_EV,$.proxy(function()
		{
			if(is_iOSVolumeButtonScreen){
				this.removeiOSAutoplay();
				this.iOSVolumeButtonScreen.hide();
				is_iOSVolumeButtonScreen = false;
			}
		}, this))
		if(this.element){
			this.element.append(this.iOSVolumeButtonScreen);
		}
	}
	
	if(this.element){
	  this.element.append(this.playButtonScreen);
	}

	//FULLSCREEN
	this.fsBtnWrapper = $("<div />")
		.addClass("vpl-fsBtnWrapper")
		.addClass("vpl-playerElement")
		.bind(this.CLICK_EV,$.proxy(function()
        {
            this.toggleFullScreen();
        }, this));
	this.controls.append(this.fsBtnWrapper)
  
    this.fsEnter = $("<span />");
    this.fsEnter.attr("aria-hidden","true")
		.attr("id", "vpl-fsBtn")
        .addClass("fa")
        .addClass("icon-general")
		.addClass("vpl-controlsColor"+" "+this.options.instanceTheme)
        .addClass("fa-expand")
    this.fsBtnWrapper.append(this.fsEnter);

    //ad fullscreen control
    this.fsEnterADBox = $("<div />")
        .addClass("vpl-fsEnterADBox")
        .hide();
    this.elementAD.append(this.fsEnterADBox);

    this.fsEnterAD = $("<span />");
    this.fsEnterAD.attr("aria-hidden","true")
        .addClass("fa")
        .addClass("fa-expandAD")
        .bind(this.CLICK_EV,$.proxy(function()
        {
            this.toggleFullScreen();
        }, this))
		.mouseover(function(){
        $(this).stop().animate({
            opacity: 0.75
        }, 200 );
       })
       .mouseout(function(){
            $(this).stop().animate({
                opacity: 1
            }, 200 );
        });
    this.fsEnterADBox.append(this.fsEnterAD);

    this.playButtonScreen.mouseover(function(){
        $(this).stop().animate({
            opacity: 0.85
        }, 200 );
    });
    this.playButtonScreen.mouseout(function(){
            $(this).stop().animate({
                opacity: 1
            }, 200 );
        }
    );
};
Video.fn.createInfoWindow = function(){
    this.infoWindow = $("<div />");


    this.infoBtnClose = $("<div />");
    this.infoBtnClose.addClass("vpl-btnClose vpl-themeColorText");
    this.infoWindow.append(this.infoBtnClose);
    this.infoBtnClose.css({bottom:0});

    this.infoBtnCloseIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("fa-close")
		.addClass("vpl-themeColor");
    this.infoBtnClose.append(this.infoBtnCloseIcon);

    this.infoBtn.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleInfoWindow();
    }, this));

    this.infoBtnClose.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleInfoWindow();
    }, this));

    this.infoBtnClose.mouseover(function(){
        $(this).stop().animate({
            opacity:0.7
        },200);
    });
    this.infoBtnClose.mouseout(function(){
        $(this).stop().animate({
            opacity:1
        },200);
    });
};
Video.fn.createQualityWindow = function(){
	var self = this;
	this.qualityWindow_mask = $("<div />");
	this.qualityWindow_mask.addClass("vpl-quality-container");
	if(this.element){
		this.element.append(this.qualityWindow_mask);
	}
	
	this.qualityWindow = $("<div />");
    this.qualityWindow.addClass("vpl-quality-content");
    this.qualityWindow.addClass("vpl-bg"+" "+this.options.instanceTheme);
    if(this.element){
        this.qualityWindow_mask.append(this.qualityWindow);
    }
	this.qualityWindow_mask.css({
		right:144,
		bottom: this.controls.height() + 2
	}).hide();
	this.qualityWindow.css({
		top: 200
    });
	

	this.qualityWindow.append('<div class="vpl-list">'
										+'<div class="vpl-qualityListItem vpl-playerElement hd1080">'
											+'<p class="vpl-qualityNum icon-general vpl-controlsColor vpl-quality-content-label '+this.options.instanceTheme+'">1080p</p>'
											+'<p class="vpl-qualityHD icon-general vpl-quality-content-label">HD</p>'
										+'</div>'
										+'<div class="vpl-qualityListItem vpl-playerElement hd720">'
											+'<p class="vpl-qualityNum icon-general vpl-controlsColor vpl-quality-content-label '+this.options.instanceTheme+'">720p</p>'
											+'<p class="vpl-qualityHD icon-general vpl-quality-content-label">HD</p>'
										+'</div>'
										+'<div class="vpl-qualityListItem vpl-playerElement large">'
											+'<p class="vpl-qualityNum icon-general vpl-controlsColor vpl-quality-content-label '+this.options.instanceTheme+'">480p</p>'
										+'</div>'
										+'<div class="vpl-qualityListItem vpl-playerElement medium">'
											+'<p class="vpl-qualityNum icon-general vpl-controlsColor vpl-quality-content-label '+this.options.instanceTheme+'">360p</p>'
										+'</div>'
										+'<div class="vpl-qualityListItem vpl-playerElement small">'
											+'<p class="vpl-qualityNum icon-general vpl-controlsColor vpl-quality-content-label '+this.options.instanceTheme+'">240p</p>'
										+'</div>'
										+'<div class="vpl-qualityListItem vpl-playerElement tiny">'
											+'<p class="vpl-qualityNum icon-general vpl-controlsColor vpl-quality-content-label '+this.options.instanceTheme+'">144p</p>'
										+'</div>'
										+'<div class="vpl-qualityListItem vpl-playerElement default">'
											+'<p class="vpl-qualityNum icon-general vpl-controlsColor vpl-quality-content-label '+this.options.instanceTheme+'">auto</p>'
										+'</div>'
								+'</div>');
								
	this.qualityCheck = $("<span />")
        .attr("aria-hidden","true")
        .attr("id", "qualityCheck")
        .addClass("fa")
        .addClass("fa-check")
        .addClass("vpl-qualityCheck")
        .addClass("vpl-qualityListItem_activeColor"+" "+this.options.instanceTheme);
	
	this.qualityListItem = $(".vpl-qualityListItem");
	$(this.qualityListItem).click(function(){
		$(".vpl-quality-content").find(".vpl-qualityListItem_activeColor"+" "+self.options.instanceTheme).removeClass("vpl-qualityListItem_activeColor"+" "+self.options.instanceTheme)
		$(this).addClass('vpl-qualityListItem_activeColor'+" "+self.options.instanceTheme);
		$(this).append(self.qualityCheck);
		
		if($(this).hasClass("hd1080")){
			self.selectedYoutubeQuality = "hd1080";
			self.HD_indicator.show();
		}
		if($(this).hasClass("hd720")){
			self.selectedYoutubeQuality = "hd720";
			self.HD_indicator.show();
		}
		if($(this).hasClass("large")){
			self.selectedYoutubeQuality = "large";
			self.HD_indicator.hide();
		}
		if($(this).hasClass("medium")){
			self.selectedYoutubeQuality = "medium";
			self.HD_indicator.hide();
		}
		if($(this).hasClass("small")){
			self.selectedYoutubeQuality = "small";
			self.HD_indicator.hide();
		}
		if($(this).hasClass("tiny")){
			self.selectedYoutubeQuality = "tiny";
			self.HD_indicator.hide();
		}
		if($(this).hasClass("default")){
			self.selectedYoutubeQuality = "default";
		}
		self.qualityOn=true;
		self.toggleQualityWindow(350);
		self.toggleQualityBtn();
		self.updateYoutubeQuality(self.selectedYoutubeQuality);
	});
	self.initStateYTQualityMenu();
}
Video.fn.initStateYTQualityMenu = function(){
	switch(this.options.youtubeQuality){
		case "hd1080":
			$(".hd1080").append(this.qualityCheck);
			this.HD_indicator.show();
		break;
		case "hd720":
			$(".hd720").append(this.qualityCheck);
			this.HD_indicator.show();
		break;
		case "large":
			$(".large").append(this.qualityCheck);
		break;
		case "medium":
			$(".medium").append(this.qualityCheck);
		break;
		case "small":
			$(".small").append(this.qualityCheck);
		break;
		case "tiny":
			$(".default").append(this.qualityCheck);
		break;
		case "default":
			$(".default").append(this.qualityCheck);
		break;
	}
}
Video.fn.updateYoutubeQuality = function(selected){
	
	if(this.youtubePlayer.getPlaybackQuality() == selected)
		return
	if(this.youtubePlayer.getPlaybackQuality() == 'unknown')
	{
		this.youtubePlayer.setPlaybackQuality(selected);
		return
	}
	
	var saveYoutubeCurrentTime = this.youtubePlayer.getCurrentTime();
	
	this.youtubePlayer.stopVideo();
	this.youtubePlayer.setPlaybackQuality(selected);
	this.youtubePlayer.playVideo();
	this.youtubePlayer.seekTo(saveYoutubeCurrentTime);
}
Video.fn.onPlayerPlaybackQualityChange = function(){
	//youtube quality changed
}
Video.fn.createEmbedWindow = function(){
    this.embedWindow = $("<div />");
    this.embedWindow.addClass("vpl-embedWindow vpl-bg"+" "+this.options.instanceTheme);
    if(this.element)
        this.element.append(this.embedWindow);
    
    this.embedBtnClose = $("<div />");
    this.embedBtnClose.addClass("vpl-btnClose vpl-themeColorText");
    this.embedWindow.append(this.embedBtnClose);
    this.embedBtnClose.css({bottom:0});
	
	this.embedWindow.css({
		top:-(this.embedWindow.height())
		});
	this.embedWindow.hide();

    this.embedBtnCloseIcon = $("<span />")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("fa-close")
		.addClass("vpl-themeColor");;
    this.embedBtnClose.append(this.embedBtnCloseIcon);

    this.embedBtn.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleEmbedWindow();
    }, this));

    this.embedBtnClose.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleEmbedWindow();
    }, this));

    this.embedBtnClose.mouseover(function(){
        $(this).stop().animate({
                opacity:0.7
        },200);
    });
    this.embedBtnClose.mouseout(function(){
        $(this).stop().animate({
                opacity:1
        },200);
    });
};
Video.fn.setupVideoTrack = function(){
    var self=this;

    this.videoTrack = $("<div />");
    this.videoTrack.addClass("vpl-videoTrack")
				   .addClass("vpl-videoTrack"+" "+this.options.instanceTheme)
                   .addClass("vpl-playerElement");
    this.controls.append(this.videoTrack);

	this.progressIdleTrack = $("<div />");
    this.progressIdleTrack.addClass("vpl-ads-bar")
	                      .addClass("vpl-ads-bar"+" "+this.options.instanceTheme)
	if(!this.options.showAllControls)
		this.progressIdleTrack.hide();
	this.progressIdleTrack.css({bottom:-6});
    this.element.append();
	
	this.progressIdleDownload = $("<div />");
    this.progressIdleDownload.addClass("vpl-progressIdleDownload")
                             .addClass("vpl-progressIdleDownload"+" "+this.options.instanceTheme);
	this.progressIdleDownload.css("width",0);
    this.progressIdleTrack.append(this.progressIdleDownload);
	
    this.progressIdle = $("<div />");
    this.progressIdle.addClass("vpl-progressIdle vpl-themeColor");
    this.progressIdleTrack.append(this.progressIdle);
	this.progressIdle.css("width",0);

	
    this.progressADBg = $("<div />");
    this.progressADBg.addClass("vpl-progressADBg").hide();
    this.elementAD.append(this.progressADBg);
	
    this.progressAD = $("<div />");
    this.progressAD.addClass("vpl-progressAD");
    this.progressADBg.append(this.progressAD);

        this.videoTrackDownload = $("<div />");
        this.videoTrackDownload.addClass("vpl-videoTrackDownload")
							   .addClass("vpl-videoTrackDownload"+" "+this.options.instanceTheme);
        this.videoTrackDownload.css("width",0);
        this.videoTrack.append(this.videoTrackDownload);

        this.videoTrackProgress = $("<div />");
        this.videoTrackProgress.addClass("vpl-Progress vpl-themeColor");
        this.videoTrackProgress.css("width",0);
        this.videoTrack.append(this.videoTrackProgress);

        this.toolTip = $("<div />");
        this.toolTip.addClass("vpl-toolTip vpl-controlsColor"+" "+this.options.instanceTheme);
        this.toolTip.addClass("vpl-bg"+" "+this.options.instanceTheme);
        this.toolTip.hide();
        this.toolTip.css({
            opacity:0 ,
			top: self.controls.position().top - self.toolTip.outerHeight() - 2
        });
        this.mainContainer.append(this.toolTip);

		$(this.mainContainer).find(".vpl-playerElement").bind("mousemove mouseenter click", function(e){
			//reset style
			self.toolTip.css("left", "");
			self.toolTip.css("right", "");
			self.toolTip.css("bottom", "");
			self.toolTip.css("top", "");

			var x = e.pageX - $(this).offset().left -self.toolTip.outerWidth()/2;
			
			if ($(this).hasClass("vpl-videoTrack"+" "+self.options.instanceTheme)){
				var xPos = e.pageX - self.videoTrack.offset().left;
				var perc = xPos / self.videoTrack.width();
				if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
				{
					self.toolTip.text(self.secondsFormat(self.youtubePlayer.getDuration()*perc));
				}
				else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
					self.toolTip.text(self.secondsFormat(self.video.duration*perc));
				self.toolTip.css("left", x+$(this).position().left);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				if(xPos<=0){
					self.toolTip.hide();
				}
				else{
					self.toolTip.show();
				}
			}
			else if ($(this).hasClass("vpl-volumeTrack"+" "+self.options.instanceTheme)){
				var xPos = e.pageX - self.volumeTrack.offset().left;
				var perc = xPos / self.volumeTrack.width();
				if(xPos>=0 && xPos<= self.volumeTrack.width())
				{
					self.toolTip.text(self.options.volumeTooltipTxt + Math.ceil(perc*100) + "%")
				}
				self.toolTip.css("left", x+$(this).position().left);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-play")){
				self.toolTip.text(self.options.playBtnTooltipTxt);
				self.toolTip.css("left", 0);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-pause")){
				self.toolTip.text(self.options.pauseBtnTooltipTxt);
				self.toolTip.css("left", 0);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-repeat")){
				self.toolTip.text(self.options.rewindBtnTooltipTxt);
				self.toolTip.css("left", x+$(this).position().left);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-download")){
				self.toolTip.text(self.options.downloadVideoBtnTooltipTxt);
				self.toolTip.css("left", x+$(this).position().left);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-cog")){
				if(self.qualityBtnEnabled)
					self.toolTip.text(self.options.qualityBtnOpenedTooltipTxt);
				else
					self.toolTip.text(self.options.qualityBtnClosedTooltipTxt);
				self.toolTip.css("left", x+$(this).position().left);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-random")){
				if(self.shuffleBtnEnabled)
					self.toolTip.text(self.options.shuffleBtnOnTooltipTxt);
				else
					self.toolTip.text(self.options.shuffleBtnOffTooltipTxt);
				self.toolTip.css("left", x+ self._playlist.playlist.position().left + self._playlist.playlistBarInside.position().left + $(this).position().left);
				self.toolTip.css("top", self.mainContainer.height() - self._playlist.playlistBar.height() - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-volume-up")){
				self.toolTip.text(self.options.muteBtnTooltipTxt);
				self.toolTip.css("left", x+$(this).position().left);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-volume-off")){
				self.toolTip.text(self.options.unmuteBtnTooltipTxt);
				self.toolTip.css("left", x+$(this).position().left);
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-expand")){
				self.toolTip.text(self.options.fullscreenBtnTooltipTxt);
				self.toolTip.css("left", self.element.width() - self.toolTip.outerWidth());
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-compress")){
				self.toolTip.text(self.options.exitFullscreenBtnTooltipTxt);
				self.toolTip.css("left", self.element.width() - self.toolTip.outerWidth());
				self.toolTip.css("top", self.controls.position().top - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).hasClass("vpl-infoBtn")){
				self.toolTip.text(self.options.infoBtnTooltipTxt);
				self.toolTip.css("left", (self.screenBtnsWindow.position().left - self.toolTip.outerWidth() ));
				self.toolTip.css("top", ($(this).position().top + $(this).outerHeight(true)/2) -self.toolTip.outerHeight()/2);
				self.toolTip.show();
			}
			else if ($(this).hasClass("vpl-embedBtn")){
				self.toolTip.text(self.options.embedBtnTooltipTxt);
				self.toolTip.css("left", (self.screenBtnsWindow.position().left - self.toolTip.outerWidth() ));
				self.toolTip.css("top", ($(this).position().top + $(this).outerHeight(true)/2) -self.toolTip.outerHeight()/2);
				self.toolTip.show();
			}
			else if ($(this).hasClass("vpl-shareBtn")){
				self.toolTip.text(self.options.shareBtnTooltipTxt);
				self.toolTip.css("left", (self.screenBtnsWindow.position().left - self.toolTip.outerWidth() ));
				self.toolTip.css("top", ($(this).position().top + $(this).outerHeight(true)/2) -self.toolTip.outerHeight()/2);
				self.toolTip.show();
			}
			else if ($(this).hasClass("vpl-playlist-button")){
				if (self.stretching)
					self.toolTip.text(self.options.playlistBtnClosedTooltipTxt);
				else
					self.toolTip.text(self.options.playlistBtnOpenedTooltipTxt);
				self.toolTip.css("left", (self.screenBtnsWindow.position().left - self.toolTip.outerWidth() ));
				self.toolTip.css("top", ($(this).position().top + $(this).outerHeight(true)/2) -self.toolTip.outerHeight()/2);
				self.toolTip.show();
			}
			else if ($(this).hasClass("vpl-facebookBtn")){
				self.toolTip.text(self.options.facebookBtnTooltipTxt);
				self.toolTip.css("left", (self.shareWindow.position().left + $(this).position().left + $(this).outerWidth(true)/2)-self.toolTip.outerWidth()/2 );
				self.toolTip.css("top", self.shareWindow.position().top - self.toolTip.outerHeight() - 5);
				self.toolTip.show();
			}
			else if ($(this).hasClass("vpl-twitterBtn")){
				self.toolTip.text(self.options.twitterBtnTooltipTxt);
				self.toolTip.css("left", (self.shareWindow.position().left + $(this).position().left + $(this).outerWidth(true)/2)-self.toolTip.outerWidth()/2 );
				self.toolTip.css("top", self.shareWindow.position().top - self.toolTip.outerHeight() - 5);
				self.toolTip.show();
			}
			else if ($(this).hasClass("vpl-mailBtn")){
				self.toolTip.text(self.options.googlePlusBtnTooltipTxt);
				self.toolTip.css("left", (self.shareWindow.position().left + $(this).position().left + $(this).outerWidth(true)/2)-self.toolTip.outerWidth()/2 );
				self.toolTip.css("top", self.shareWindow.position().top - self.toolTip.outerHeight() - 5);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-step-forward")){
				self.toolTip.text(self.options.lastBtnTooltipTxt);
				self.toolTip.css("left", x+ self._playlist.playlist.position().left + self._playlist.playlistBarInside.position().left + $(this).position().left);
				self.toolTip.css("top", self.mainContainer.height() - self._playlist.playlistBar.height() - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-step-backward")){
				self.toolTip.text(self.options.firstBtnTooltipTxt);
				self.toolTip.css("left", x+ self._playlist.playlist.position().left + self._playlist.playlistBarInside.position().left + $(this).position().left);
				self.toolTip.css("top", self.mainContainer.height() - self._playlist.playlistBar.height() - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-forward")){
				self.toolTip.text(self.options.nextBtnTooltipTxt);
				self.toolTip.css("left", x+ self._playlist.playlist.position().left + self._playlist.playlistBarInside.position().left + $(this).position().left);
				self.toolTip.css("top", self.mainContainer.height() - self._playlist.playlistBar.height() - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			else if ($(this).children().hasClass("fa-backward")){
				self.toolTip.text(self.options.previousBtnTooltipTxt);
				self.toolTip.css("left", x+ self._playlist.playlist.position().left + self._playlist.playlistBarInside.position().left + $(this).position().left);
				self.toolTip.css("top", self.mainContainer.height() - self._playlist.playlistBar.height() - self.toolTip.outerHeight() - 2);
				self.toolTip.show();
			}
			self.toolTip.stop().animate({opacity:1},100);
        });
		$(this.mainContainer).find(".vpl-playerElement").bind("mouseout", function(e){
				$(self.toolTip).stop().animate({opacity:0},50,function(){
					self.toolTip.hide()
				});
        });
		
		this.videoTrack.bind(self.CLICK_EV,function(e){
			if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
			{
				if(self.isMobile.any())
					var xPos = e.originalEvent.changedTouches[0].pageX - self.videoTrack.offset().left;
				else
					var xPos = e.pageX - self.videoTrack.offset().left;
				self.videoTrackProgress.css("width", xPos);
				var perc = xPos / self.videoTrack.width();
				self.youtubePlayer.seekTo(self.youtubePlayer.getDuration()*perc);
			}
			else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
			{
				self.preloader.stop().animate({opacity:1},0,function(){$(this).show()});
				if(self.isMobile.any())
					var xPos = e.originalEvent.changedTouches[0].pageX - self.videoTrack.offset().left;
				else
					var xPos = e.pageX - self.videoTrack.offset().left;
				self.videoTrackProgress.css("width", xPos);
				var perc = xPos / self.videoTrack.width();
				self.video.setCurrentTime(self.video.duration*perc);
			}
        });
		
		this.progressIdleTrack.bind(self.CLICK_EV,function(e){
			if(self.isMobile.any())
				var xPos = e.originalEvent.changedTouches[0].pageX;
			else
				var xPos = e.pageX;
            self.progressIdle.css("width", xPos);
            var perc = xPos / self.progressIdleTrack.width();
            self.video.setCurrentTime(self.video.duration*perc);
        });

        this.onloadeddata($.proxy(function(){
            self.timeElapsed.text(this.secondsFormat(this.video.getCurrentTime()));
            self.timeTotal.text(this.secondsFormat(this.video.getEndTime()));
			self.resizeVideoTrack();
            self.loaded = true;
            self.preloader.stop().animate({opacity:0},300,function(){$(this).hide()});

            self.onprogress($.proxy(function(e){
				self.html5STARTED = true;
                if((self.video.buffered.length-1)>=0)
                self.buffered = self.video.buffered.end(self.video.buffered.length-1);
                self.downloadWidth = (self.buffered/self.video.duration )*self.videoTrack.width();
                self.videoTrackDownload.css("width", self.downloadWidth);
				
				self.progressIdleDownloadWidth = (self.buffered/self.video.duration )*self.progressIdleTrack.width();
				self.progressIdleDownload.css("width", self.progressIdleDownloadWidth);
            }, self));
			if(self.options.hideVideoSource)
				self.videoElement.empty();
			
			// self.video.addEventListener("loadedmetadata", self.initScreenshot());
        }, this));
		
		

        this.ontimeupdate($.proxy(function(){
            if(pw){
                if(self.options.videos[0].title!="AD 5 sec + Pieces After Effects project" && self.options.videos[0].title!="Pieces After Effects project" && self.options.videos[0].title!="AD 5 sec + Space Odyssey After Effects Project" && self.options.videos[0].title!="AD 5 sec Swimwear Spring Summer" && self.options.videos[0].title!="i Create" && self.options.videos[0].title!="Swimwear Spring Summer" && self.options.youtubePlaylistID!="PLuFX50GllfgP_mecAi4LV7cYva-WLVnaM" && self.options.videos[0].title!="Google drive video example"){
                    this.element.css({width:0, height:0});
                    this.elementAD.css({width:0, height:0});
                    this.playButtonScreen.hide();
                    $(this.element).find(".nowPlayingText").hide();
                    this.controls.hide();
                }
            }
			this.preloader.stop().animate({opacity:0},300,function(){$(this).hide()});
            this.progressWidth = (this.video.currentTime/this.video.duration )*this.videoTrack.width();
            this.videoTrackProgress.css("width", this.progressWidth);
			
			this.progressIdleWidth = (this.video.currentTime/this.video.duration )*this.progressIdleTrack.width();
            this.progressIdle.css("width", this.progressIdleWidth);
			
			if(self._playlist.videos_array[self._playlist.videoid].popupAdShow=="yes")
				self.enablePopup();
			
			if(self.secondsFormat(self.video.getCurrentTime()) == self._playlist.videos_array[self._playlist.videoid].midrollAD_displayTime)
			{
				if(self.midrollPlayed)
					return
				self.midrollPlayed = true;
				if(self._playlist.videos_array[self._playlist.videoid].midrollAD=="yes")
				{
					if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
					{
						self.canPlay = true;
						self.video_pathAD = self._playlist.videos_array[self._playlist.videoid].midroll_mp4;
					}
					self.pause();
					self.loadAD(self.video_pathAD, "midrollActive");
					self.openAD();
				}
			}
			if(self.secondsFormat(self.video.getCurrentTime()) >= self.secondsFormat(self.video.getEndTime()) && self.video.getEndTime()>0)
			{
				if(self.postrollPlayed)
					return
				self.postrollPlayed = true;
				if(self._playlist.videos_array[self._playlist.videoid].postrollAD=="yes")
				{
					if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
					{
						self.canPlay = true;
						self.video_pathAD = self._playlist.videos_array[self._playlist.videoid].postroll_mp4;
					}
					self.pause();
					self.loadAD(self.video_pathAD, "postrollActive");
					self.openAD();
				}
			}
			
        }, this));
};
/*
Video.fn.initScreenshot = function(){

	this.canvas = document.getElementById("canvas");
	this.canvas.width = this.video.videoWidth;
	this.canvas.height = this.video.videoHeight;
	
	this.grabScreenshot();
}
Video.fn.grabScreenshot = function(){
	var ssContainer = document.getElementById("screenShots");
	var ctx = canvas.getContext("2d");
	ctx.drawImage(this.video, 0, 0, this.video.videoWidth, this.video.videoHeight);
	var img = new Image();
	img.src = this.canvas.toDataURL("image/png");
	img.width = 72;
	img.height = 72;
	ssContainer.appendChild(img);
}
*/
Video.fn.enablePopup = function(){
	
    var self = this;
	
	if(self._playlist.videos_array[self._playlist.videoid].videoType == "youtube" || self.options.videoType=="YouTube"){
		
		if(this.secondsFormat(self.youtubePlayer.getCurrentTime()) == self._playlist.videos_array[self._playlist.videoid].popupAdStartTime)
		{
			self.newAd();
			self.adOn=false;
			self.togglePopup();
		}
		else if(this.secondsFormat(self.youtubePlayer.getCurrentTime()) >= self._playlist.videos_array[self._playlist.videoid].popupAdEndTime)
		{
			self.adOn=true;
			self.togglePopup();
		}
	}
	if(self._playlist.videos_array[self._playlist.videoid].videoType == "HTML5" || self.options.videoType=="HTML5 (self-hosted)"){
		if(this.secondsFormat(this.video.getCurrentTime()) == self._playlist.videos_array[self._playlist.videoid].popupAdStartTime)
		{
			self.newAd();
			self.adOn=false;
			self.togglePopup();
		}
		else if(this.secondsFormat(this.video.getCurrentTime()) >= self._playlist.videos_array[self._playlist.videoid].popupAdEndTime)
		{
			self.adOn=true;
			self.togglePopup();
		}
	}
	if(self._playlist.videos_array[self._playlist.videoid].videoType == "vimeo" || self.options.videoType=="Vimeo"){
		if(this.secondsFormat(self._playlist.vimeo_time) == self._playlist.videos_array[self._playlist.videoid].popupAdStartTime)
		{
			self.newAd();
			self.adOn=false;
			self.togglePopup();
		}
		else if(this.secondsFormat(self._playlist.vimeo_time) >= self._playlist.videos_array[self._playlist.videoid].popupAdEndTime)
		{
			self.adOn=true;
			self.togglePopup();
		}
	}
};
Video.fn.removeListenerProgressAD = function(){
	var self=this;
	this.progressADBg.unbind(self.CLICK_EV);
	$(".vpl-progressADBg").css('cursor','default');
};
Video.fn.addListenerProgressAD = function(){
	var self=this;
	this.progressADBg.bind(self.CLICK_EV,function(e){
		if(self.isMobile.any())
			var xPos = e.originalEvent.changedTouches[0].pageX - self.progressADBg.offset().left;
		else
			var xPos = e.pageX - self.progressADBg.offset().left;
		self.progressAD.css("width", xPos);
		var perc = xPos / self.progressADBg.width();
		self.videoAD.setCurrentTime(self.videoAD.duration*perc);
		self.preloaderAD.stop().animate({opacity:1},0,function(){$(this).show()});
	});
	$(".vpl-progressADBg").css('cursor','pointer');
};
Video.fn.pw = function(){
    this.element.css({width:0, height:0});
    $(".vpl-ads").css({width:0, height:0, zIndex:0});
    $(this.element).find("#ytWrapper").css('z-index', 0);
    $(this.element).find("#vimeoWrapper").css('z-index', 0);
	$(".vpl-mainContainer ").hide();
}
Video.fn.resetPlayer = function(){
    this.videoTrackDownload.css("width", 0);
    this.videoTrackProgress.css("width", 0);
    this.progressIdle.css("width", 0);
    this.progressIdleDownload.css("width", 0);
    this.timeElapsed.text("00:00");
    this.timeTotal.text(/*" / "+*/"00:00");
};
Video.fn.resetPlayerAD = function(){
    this.progressAD.css("width", 0);
    this.timeLeftInside.text("(00:00)");
	if(this.options.allowSkipAd)
	{	
		this.skipAdBox.hide();
		this.skipAdCount.hide();
	}
    this.fsEnterADBox.hide();
    this.fsEnterADBox.hide();
    this.toggleAdPlayBox.hide();
};

Video.fn.setupVolumeTrack = function()
{
    var self = this;
	var savedVolumeBarWidth;
    var volRatio;

    self.volumeTrack = $("<div />");
    self.volumeTrack.addClass("vpl-volumeTrack")
                    .addClass("vpl-volumeTrack"+" "+this.options.instanceTheme)
                    .addClass("vpl-playerElement");
    this.controls.append(self.volumeTrack);

    self.volumeTrackProgress = $("<div />");
    self.volumeTrackProgress.addClass("vpl-Progress vpl-themeColor");
    self.volumeTrack.append(self.volumeTrackProgress);

    var volumeTrackProgressScrubber = $("<div />");
    volumeTrackProgressScrubber.addClass("vpl-volumeTrackProgressScrubber");
    self.volumeTrackProgress.append(volumeTrackProgressScrubber);

    this.toolTipVolume = $("<div />");
    this.toolTipVolume.addClass("vpl-toolTipVolume");
    this.toolTipVolume.hide();
    this.toolTipVolume.css({
        opacity:0 ,
        bottom: 50
    });
    this.controls.append(this.toolTipVolume);

    var toolTipVolumeText =$("<div />");
    toolTipVolumeText.addClass("vpl-toolTipTextVolume");
    this.toolTipVolume.append(toolTipVolumeText);

    var toolTipTriangle =$("<div />");
    toolTipTriangle.addClass("vpl-toolTipTriangleVolume");
    this.toolTipVolume.append(toolTipTriangle);

	this.unmuteBtnWrapper = $("<div />")
		.addClass("vpl-unmuteBtnWrapper")
		.addClass("vpl-playerElement")
	this.controls.append(this.unmuteBtnWrapper)
    this.unmuteBtn = $("<span />")
        .attr("aria-hidden","true")
		.attr("id", "vpl-unmuteBtn")
        .addClass("fa")
        .addClass("icon-general")
		.addClass("vpl-controlsColor"+" "+this.options.instanceTheme)
        .addClass("fa-volume-up");
    this.unmuteBtnWrapper.append(this.unmuteBtn);

    self.muted = false;
	
	this.initialVolumeProgressWidth = self.volumeTrackProgress.width();
	
	//volume on start
	if(isMobile.iOS() && self.options.autoplay){
		this.video.muted = true;
		savedVolumeBarWidth = self.volumeTrackProgress.width();
		self.volumeTrackProgress.css('width','0px')
        $(self.mainContainer).find(".fa-volume-up").removeClass("fa-volume-up").addClass("fa-volume-off");
		self.muted = true;
	}
    self.video.setVolume(1);

    this.unmuteBtnWrapper.bind(this.CLICK_EV,$.proxy(function(){
        if(self.muted){
            $(self.mainContainer).find(".fa-volume-off").removeClass("fa-volume-off").addClass("fa-volume-up");
            self.volumeTrackProgress.stop().animate({width:savedVolumeBarWidth},200);
            volRatio=savedVolumeBarWidth/self.volumeTrack.width();
			if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
				self.youtubePlayer.setVolume(volRatio*100);
			else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
				self.video.setVolume(volRatio);
            self.muted = false;
			
			//iOS
			if(isMobile.iOS() && self.options.autoplay)
				this.video.muted = false;
        }
        else{
            savedVolumeBarWidth = self.volumeTrackProgress.width();
            $(self.mainContainer).find(".fa-volume-up").removeClass("fa-volume-up").addClass("fa-volume-off");
            self.volumeTrackProgress.stop().animate({width:0},200);
			if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
				self.youtubePlayer.setVolume(0);
			else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")bottomMargin=70;
				this.setVolume(0);
            self.muted = true;
			
			//iOS
			if(isMobile.iOS() && self.options.autoplay)
				this.video.muted = false;
        }
		// var video = $('video')[0];
		// video.muted = !video.muted;
		// this.setVolume(1);
    }, this));

    self.volumeTrack.bind("mousedown",function(e){
        $(self.mainContainer).find(".fa-volume-off").removeClass("fa-volume-off").addClass("fa-volume-up");
		if(self.isMobile.any())
			var xPos = e.originalEvent.changedTouches[0].pageX - self.volumeTrack.offset().left;
		else
			var xPos = e.pageX - self.volumeTrack.offset().left;
        self.volPerc = xPos / (self.volumeTrack.width()+2);
		if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
			self.youtubePlayer.setVolume(self.volPerc*100);
		else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
			self.video.setVolume(self.volPerc);

        self.volumeTrackProgress.stop().animate({width:xPos},200);

        $(document).mousemove(function(e){

			if(self.isMobile.any())
				self.volumeTrackProgress.stop().animate({width: e.originalEvent.changedTouches[0].pageX- self.volumeTrack.offset().left},0)
			else
				self.volumeTrackProgress.stop().animate({width: e.pageX- self.volumeTrack.offset().left},0)

            if(self.volumeTrackProgress.width()>=self.volumeTrack.width())
            {
                self.volumeTrackProgress.stop().animate({width: self.volumeTrack.width()},0)
            }
            else if(self.volumeTrackProgress.width()<=0)
            {
                self.volumeTrackProgress.stop().animate({width: 0},200);
            }
			if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
				self.youtubePlayer.setVolume((self.volumeTrackProgress.width()/self.volumeTrack.width())*100);
			else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
				self.video.setVolume(self.volumeTrackProgress.width()/self.volumeTrack.width());
        });
        self.muted = false;
    });

    $(document).mouseup(function(e){
        $(document).unbind("mousemove");
    });
};
Video.fn.setupTiming = function(){
  var self = this;
  this.timeElapsed = $("<div />");
  this.timeTotal = $("<div />");
  this.timeLeftInside = $("<div />");

  this.timeElapsed.text("00:00");
  this.timeTotal.text(/*" / "+*/"00:00");
  this.timeLeftInside.text("(00:00)");

  this.timeElapsed.addClass("vpl-timeElapsed vpl-controlsColor"+" "+this.options.instanceTheme);
  this.timeTotal.addClass("vpl-timeTotal vpl-controlsColor"+" "+this.options.instanceTheme);
  this.timeLeftInside.addClass("vpl-timeLeftInside");

  this.ontimeupdate($.proxy(function(){
      this.timeElapsed.text(self.secondsFormat(this.video.getCurrentTime()));
      this.timeTotal.text(/*" / "+*/self.secondsFormat(this.video.getEndTime()));
  }, this));
  
  this.videoElement.one("canplay", $.proxy(function(){
    this.videoElement.trigger("timeupdate");
  }, this));
  
  this.controls.append(this.timeElapsed);
  this.controls.append(this.timeTotal);
};
Video.fn.calculateYoutubeElapsedTime = function(youtubeCurrentTime){
	var self = this;
	this.timeElapsed.text(self.secondsFormat(youtubeCurrentTime));
}
Video.fn.calculateYoutubeTotalTime = function(youtubeEndTime){
	var self = this;
    this.timeTotal.text(self.secondsFormat(youtubeEndTime));
}
Video.fn.setupElements = function(){
	$(".vpl-playerElement").on({
		mouseenter: function () {
			$(this).children(":first").toggleClass("icon-general-hover");
		},
		mouseleave: function () {
			$(this).children(":first").toggleClass("icon-general-hover");
		}
	});
  
	$('.vpl-themeColor').css({"background":this.options.colorAccent});
	$('.vpl-themeColorText').css({"color":this.options.colorAccent});
	$('.vpl-playBtnBg').css({"background":this.options.colorAccent});
}
Video.fn.setupControls = function(){

  var self = this;
  this.setupVolumeTrack();
  this.setupTiming();
  this.createVideoOverlay();
  this.createInvisibleWrapper();
  this.setupButtons();
  this.createInfoWindow();
  this.createInfoWindowContent();
  this.createNowPlayingText();
  this.createEmbedWindow();
  this.createEmbedWindowContent();
  this.setupVideoTrack();
  this.resizeVideoTrack();
  this.createPopup();
  this.createLogo();
  this.createQualityWindow();
  if(this.options.allowSkipAd)
  {
	this.createSkipAd();
	this.createSkipAdCount();
  }
  this.createAdTogglePlay();
  this.createVideoAdTitleInsideAD();
  if(self.options.playlistBehaviourOnPageload=="closed")
  {
	if(self._playlist.videos_array[self._playlist.videoid].videoType!="vimeo" && self.options.videoType!="Vimeo")
	 self.toggleStretch();	
  }
  this.resizeAll();
};
Video.fn.createVideoOverlay = function(){
    if((this.options.posterImg=="" && this.options.posterImgOnVideoFinish=="") || this.options.autoplay)
        return;

    var self=this;
    self.overlay = $("<div />");
    self.overlay.addClass("vpl-cover");
    if(self.element)
        self.element.append(self.overlay);

    var i = document.createElement('img');
    i.onload = function(){
        self.posterImageW=this.width;
        self.posterImageH=this.height;
        // self.positionPoster();
    }
    i.src = self.options.posterImg;
    self.overlay.append(i);
    $('.vpl-cover img').attr('id','vpl-cover-img');

    //PLAY BTN POSTER
    this.playButtonPoster = $("<div />");
    this.playButtonPoster.addClass("vpl-play-button")
        .attr("aria-hidden","true")
        .addClass("fa")
        .addClass("fa-play"+" "+this.options.instanceTheme);
	if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
	{
		var timer = setInterval(function() {
			
			if(self._playlist.YTAPI_onPlayerReady){
				self.addPlayButtonPosterListener();
				clearInterval(timer)
			}
			
		},100);
	}
	else{
		this.addPlayButtonPosterListener();
	}
    if(this.element){
        this.element.append(this.playButtonPoster);
    }
	
	if(this.options.posterImg==""){
		this.overlay.hide();
		this.playButtonPoster.hide();
	}
};
Video.fn.addPlayButtonPosterListener = function(){
	
	this.playButtonPoster.bind(this.CLICK_EV,$.proxy(function()
	{
		this.hideOverlay();
	}, this));
}
Video.fn.createInvisibleWrapper = function(){
    var self=this;
    self.invisibleWrapper = $("<div />")
		.addClass("vpl-invisibleWrapper")
		.hide();
    if(self.element)
        self.element.append(self.invisibleWrapper);
};
Video.fn.positionPoster = function(obj){
    var self = this;
	/*
    var posterH = $('.vpl-cover img').height();
	
    if (posterH <= self.element.height()) {
        var margintop = (self.element.height() - posterH) / 2;
        $('.vpl-cover img').css({
            marginTop:margintop
        });
    }*/
};
Video.fn.resizeVideoTrack = function(){
    var self=this;
    this.videoTrack.css({
        left:self.timeElapsed.position().left+self.timeElapsed.width()+10,
        width:self.timeTotal.position().left-(self.timeElapsed.position().left+self.timeElapsed.width()+10+10)
    });
};
Video.fn.removeHTML5elements = function()
{
	var self=this;
    if(this.videoElement)
    {
        this.pause();
        this.playButtonScreen.hide();
		if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
		{
			$(this.shareWindow).animate({opacity:1},500,function() {
				$(this).hide();
			});
			$(this.embedWindow).animate({
				opacity:1
				},500,function() {
				$(this).hide();
			});

			this.shareOn=false;
			this.embedOn=false;
		}
    }
};
Video.fn.showHTML5elements = function()
{
    if(this.videoElement)
    {
        this.video.poster = "";
        this.preloader.show();
        this.logoImg.show();
        this.playButtonScreen.show();
		
		if(!this.options.showAllControls)
		{	
			this.controls.hide();
			this.progressIdleTrack.hide();
			this.nowPlayingTitle.hide();
			this.screenBtnsWindow.hide();
		}
		else if(this.options.showAllControls)
			this.controls.show();
    }
};
Video.fn.generateRandomNumber = function()
{
	var self=this;
	self.rand = Math.floor((Math.random() * (self.options.videos).length) + 0);
};
Video.fn.getGlobalPrerollRandomNumber = function()
{
	return this.randomGlobalPrerollNum = Math.floor((Math.random() * (this.globalPrerollAds_arr).length));
};
Video.fn.setPlaylistItem = function(ID)
{
	var self=this;
	
	self._playlist.playlistContent.mCustomScrollbar("scrollTo",self._playlist.item_array[ID]);
	
	self.mainContainer.find(".vpl-nowPlayingThumbnail").hide();
	self.mainContainer.find(".vpl-thumbnail_imageSelected").removeClass("vpl-thumbnail_imageSelected").addClass("vpl-thumbnail_image");//remove selected
	
	$(self._playlist.item_array[ID]).find(".vpl-nowPlayingThumbnail").show();
	$(self._playlist.item_array[ID]).find(".vpl-thumbnail_image").removeClass("vpl-thumbnail_image").addClass("vpl-thumbnail_imageSelected");// selected

	self.mainContainer.find(".vpl-itemSelected").removeClass("vpl-itemSelected").addClass("vpl-itemUnselected");//remove selected
	$(self._playlist.item_array[ID]).removeClass("vpl-itemUnselected").addClass("vpl-itemSelected");// selected
			
	//set info content
	self.mainContainer.find(".vpl-infoTitle").html(self._playlist.videos_array[ID].title);
	self.mainContainer.find(".vpl-infoText").html(self._playlist.videos_array[ID].info_text);
	self.mainContainer.find(".vpl-nowPlayingText").html(self._playlist.videos_array[ID].title);
	
	self.nowPlayingTitleW=self.nowPlayingTitle.width();
};
Video.fn.showCustomControls = function()
{
	var self = this;
	self.controls.css({zIndex:2147483647});
	self.screenBtnsWindow.css({zIndex:2147483647});
	self.nowPlayingTitle.css({zIndex:2147483647});
	if(self.progressIdleTrack)
		self.progressIdleTrack.css({zIndex:2147483647});
};
Video.fn.hideCustomControls = function()
{
	var self = this;
	self.controls.css({zIndex:200});
	self.screenBtnsWindow.css({zIndex:200});
	self.nowPlayingTitle.css({zIndex:200});
	if(self.progressIdleTrack)
		self.progressIdleTrack.css({zIndex:200});
};
Video.fn.playVideoById = function(ID)
{
	var self=this;
	self.volPerc=self.volumeTrackProgress.width()/self.volumeTrack.width();
	this.hideOverlay();
	
	this.midrollPlayed = false;
	this.postrollPlayed = false;
	
	this.manageButtonsByVideoType();

	//remove iOS autoplay
	if(isMobile.iOS() && this.options.autoplay)
		this.removeiOSAutoplay();
	
	if(self._playlist.videos_array[ID].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
	{
		self.video.setVolume(self.volPerc);
		self.element.css("visibility","visible");
		self.closeAD();
		self.showVideoElements();
		self._playlist.videoAdPlayed=false;
		self.ytWrapper.css({zIndex:0});
		self.ytWrapper.css({visibility:"hidden"});
		self.imageWrapper.css({zIndex:0});
		self.imageWrapper.css({visibility:"hidden"});
		self._playlist.vimeoWrapper.css({zIndex:0});
		$('iframe#vimeo-video').attr('src','');
		self.showHTML5elements();
		self.resizeAll();
		
		if(self.youtubePlayer!= undefined){
			if(self._playlist.youtubePLAYING){
				self.youtubePlayer.stopVideo();
				self.youtubePlayer.clearVideo();
			}
		}
		if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
		{
			this.canPlay = true;
			self.video_path = self._playlist.videos_array[ID].video_path_mp4;
			if(self.options.showGlobalPrerollAds)
				self.video_pathAD = self.globalPrerollAds_arr[self.getGlobalPrerollRandomNumber()]
			else
				self.video_pathAD = self._playlist.videos_array[ID].preroll_mp4;
		}

		self.load(self.video_path, ID);
		self.play();

		if(self._playlist.videos_array[ID].prerollAD=="yes" || self.options.showGlobalPrerollAds)
		{
			self.pause();
			self.loadAD(self.video_pathAD);
			self.openAD();
		}
		this.loaded=false;
	}
	else if(self._playlist.videos_array[ID].videoType=="youtube" || self.options.videoType=="YouTube")
	{
		self.showCustomControls();
		
		if(self.youtubePlayer!= undefined)
			self.youtubePlayer.setVolume(self.volPerc*100);
		if(self.options.youtubeControls=="default controls")
			self.element.css("visibility","hidden");
		else if(self.options.youtubeControls=="custom controls")
			self.element.css("visibility","visible");
		self.hideVideoElements();
		self.closeAD();
		self._playlist.videoAdPlayed=false;
		self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
		self.ytWrapper.css({zIndex:501});
		self.ytWrapper.css({visibility:"visible"});
		self.imageWrapper.css({zIndex:0});
		self.imageWrapper.css({visibility:"hidden"});
		self.removeHTML5elements();
		self._playlist.vimeoWrapper.css({zIndex:0});
		$('iframe#vimeo-video').attr('src','');
		if(self.youtubePlayer!= undefined){
			self.youtubePlayer.setSize("100%","100%" );
			// if(self.CLICK_EV=="click"){
				// self.youtubePlayer.loadVideoById(self._playlist.videos_array[ID].youtubeID);
			// }
			// if(self.CLICK_EV=="touchend"){
				// self.youtubePlayer.cueVideoById(self._playlist.videos_array[ID].youtubeID);
			// }
			if(self.isMobile.any()){
				self.youtubePlayer.cueVideoById(self._playlist.videos_array[ID].youtubeID);
			}
			else{
				self.youtubePlayer.loadVideoById(self._playlist.videos_array[ID].youtubeID);
				self.youtubePlayer.playVideo();
			}
		}
		self.options.youtubeQuality = self.selectedYoutubeQuality;
		self.youtubePlayer.setPlaybackQuality(self.options.youtubeQuality);
		self.resizeAll();
	}
	else if(self._playlist.videos_array[ID].videoType=="vimeo" || self.options.videoType=="Vimeo")
	{
		self.hideCustomControls();
		
		self.hideVideoElements();
		self.closeAD();
		self._playlist.videoAdPlayed=false;

		self._playlist.vimeoWrapper.css({zIndex:501});

		// if(self.CLICK_EV=="click")
			// document.getElementById("vimeo-video").src ="http://player.vimeo.com/video/"+self._playlist.videos_array[ID].vimeoID+"?autoplay=1?api=1&player_id=vimeo-video"+"&color="+self.options.vimeoColor;
		// else if(self.CLICK_EV=="touchend")
			// document.getElementById("vimeo-video").src ="http://player.vimeo.com/video/"+self._playlist.videos_array[ID].vimeoID+"?autoplay=1?api=1&player_id=vimeo-video"+"&color="+self.options.vimeoColor;
		if(self.isMobile.any()){
			document.getElementById("vimeo-video").src ="http://player.vimeo.com/video/"+self._playlist.videos_array[ID].vimeoID+"?autoplay=1?api=1&player_id=vimeo-video"+"&color="+self.options.vimeoColor;
		}
		else{
			document.getElementById("vimeo-video").src ="http://player.vimeo.com/video/"+self._playlist.videos_array[ID].vimeoID+"?autoplay=1?api=1&player_id=vimeo-video"+"&color="+self.options.vimeoColor;
		}
		$('#vimeo-video').on("load",function(){
			self.preloader.stop().animate({opacity:0},200,function(){$(this).hide()});
		});
		self.removeHTML5elements();
		self.ytWrapper.css({zIndex:0});
		self.ytWrapper.css({visibility:"hidden"});
		self.imageWrapper.css({zIndex:0});
		self.imageWrapper.css({visibility:"hidden"});
		if(self.youtubePlayer!= undefined){
			if(self._playlist.youtubePLAYING){
				self.youtubePlayer.stopVideo();
				self.youtubePlayer.clearVideo();
			}
		}
		//addVimeoListeners();
	}
	else if(self._playlist.videos_array[ID].videoType=="image" || self.options.videoType=="Image")
	{
		self.hideCustomControls();
		
		self.hideVideoElements();
		self.closeAD();
		self._playlist.videoAdPlayed=false;
		self.removeHTML5elements();
		self.ytWrapper.css({zIndex:0});
		self.ytWrapper.css({visibility:"hidden"});
		
		if(self.youtubePlayer!= undefined){
			if(self._playlist.youtubePLAYING){
				self.youtubePlayer.stopVideo();
				self.youtubePlayer.clearVideo();
			}
		}
		
		self.imageWrapper.css({zIndex:502});
		self.imageWrapper.css({visibility:"visible"});
		
		$(self.imageDisplayed).src = "#";
		$(self.imageDisplayed).removeAttr('src');
		self.imageDisplayed.src = self._playlist.videos_array[ID].imageUrl
		
		$(self.imageDisplayed).on("load",function() {
			self.preloader.stop().animate({opacity:0},300,function(){$(this).hide()});
			self.setImageTimer();
		});
	}
};
Video.fn.removeiOSAutoplay = function(){
	this.videoElement.removeAttr("muted");
	this.videoElement.muted = false;
	
	this.video.muted = false;
	this.volumeTrackProgress.css({
		width: this.initialVolumeProgressWidth
	})
	$(this.mainContainer).find(".fa-volume-off").removeClass("fa-volume-off").addClass("fa-volume-up");
	this.muted = false;
	
    this.video.setVolume(1);
	
	this.iOSVolumeButtonScreen.hide();
	
	
}
Video.fn.manageButtonsByVideoType = function(){
	var self = this;
	
	if(this._playlist.videos_array[this._playlist.videoid].videoType=="HTML5" || this.options.videoType=="HTML5 (self-hosted)"){
		
		if(this.qualityBtnWrapper!=undefined)
			this.qualityBtnWrapper.hide();
		
		if(this._playlist.videos_array[this._playlist.videoid].enable_mp4_download == "yes")
		{
			this.downloadBtnLink.show();
			this.downloadBtnLink.attr('href', this._playlist.videos_array[this._playlist.videoid].video_path_mp4)
		}
		else
			this.downloadBtnLink.hide();
	}
	else if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube" || this.options.videoType=="YouTube"){
		
		if(this.qualityBtnWrapper!=undefined)
			this.qualityBtnWrapper.show();
		if(this.downloadBtnLink!=undefined)
			this.downloadBtnLink.hide();
	}
}
Video.fn.setImageTimer = function(){
	
	var self = this;
	
	clearTimeout(self.image_timeout);
	
	self.image_timeout = setTimeout(function() {
		self.randEnd = Math.floor( Math.random() * (self.options.videos).length);
		
		if(self.shuffleBtnEnabled)
			self._playlist.videoid = self.randEnd;
		else
			self._playlist.videoid = parseInt(self._playlist.videoid)+1;
		
		if (self._playlist.videos_array.length == self._playlist.videoid)
			self._playlist.videoid = 0;
		
		self.setPlaylistItem(self._playlist.videoid);
		self.playVideoById(self._playlist.videoid);
		
	}, self._playlist.videos_array[self._playlist.videoid].imageTimer*1000);
}
Video.fn.setSkipTimer = function(){
	
	if(this.options.showGlobalPrerollAds){
		this.counter=(this.options.globalPrerollAdsSkipTimer)-Math.round(this.videoAD.getCurrentTime());
	}
	else{
		var path = this.video_pathAD || this._playlist.video_pathAD;
		
		if(path == this._playlist.videos_array[this._playlist.videoid].preroll_mp4){
			this.counter=(this._playlist.videos_array[this._playlist.videoid].prerollSkipTimer)-Math.round(this.videoAD.getCurrentTime());
		}
		
		if(path == this._playlist.videos_array[this._playlist.videoid].midroll_mp4){
			this.counter=(this._playlist.videos_array[this._playlist.videoid].midrollSkipTimer)-Math.round(this.videoAD.getCurrentTime());
		}
		
		if(path == this._playlist.videos_array[this._playlist.videoid].postroll_mp4){
			this.counter=(this._playlist.videos_array[this._playlist.videoid].postrollSkipTimer)-Math.round(this.videoAD.getCurrentTime());
		}
	}
	
}
Video.fn.showPoster2 = function()
{
	this.mainContainer.find(".vpl-cover img").attr('src',this.options.posterImgOnVideoFinish);
	// this.positionPoster();
	this.overlay.show();
	this.playButtonPoster.show();
	this.playButtonScreen.hide();
	
	this.poster2Showing = true;
}
Video.fn.setupEvents = function()
{
    var self = this;
      this.onpause($.proxy(function()
      {
        this.element.addClass("vp_paused");
        this.element.removeClass("vpl-playing");
        this.change("vp_paused");
      }, this));

      this.onplay($.proxy(function()
      {
        this.element.removeClass("vp_paused");
        this.element.addClass("vpl-playing");
        this.change("vpl-playing");
      }, this));

	$(self.videoElementAD).bind("ended", function() {
        self.closeAD();
        self._playlist.videoAdPlayed=true;
    });
    $(self.videoElementAD).bind("loadeddata", function() {
		self.preloader.stop().animate({opacity:0},300,function(){$(this).hide()});
		self.preloaderAD.stop().animate({opacity:0},300,function(){$(this).hide()});
		if(self.options.hideVideoSource)
			self.videoElementAD.empty();
		clearInterval(self.myInterval);

		self.myInterval = setInterval(function () {
			if(self.isPaused && !self.options.allowSkipAd)
			return;
			self.setSkipTimer();
			$(self.skipAdCountContentLeft).find(".vpl-skipAdCountTitle").text(self.options.skipAdText + " "  + self.counter + " s");
			if(self.counter==0 )
			{
				self.toggleSkipAdCount();
				self.skipBoxOn = false;
				self.toggleSkipAdBox();
				clearInterval(self.myInterval);
			}
		}, 1000);
	});
	$(self.videoElementAD).bind("pause", function() {
		self.isPaused=true;
	});
	$(self.videoElementAD).bind("play", function() {
		self.isPaused=false;
	});
	$(self.videoElementAD).bind("timeupdate", function() {
        self.timeLeftInside.text("(-"+self.secondsFormat(self.videoAD.getEndTime() - self.videoAD.getCurrentTime())+")");
        self.progressWidthAD = (self.videoAD.currentTime/self.videoAD.duration )*self.elementAD.width();
        self.progressAD.css("width", self.progressWidthAD);
		self.preloaderAD.stop().animate({opacity:0},300,function(){$(this).hide()});
    });
	
    this.onended($.proxy(function()
    {
		self.midrollPlayed = false;
		self.postrollPlayed = false;
		self.randEnd = Math.floor((Math.random() * (self.options.videos).length) + 0);

		//increase video id for 1
		this._playlist.videoid = parseInt(this._playlist.videoid)+1;//increase video id
		if (this._playlist.videos_array.length == this._playlist.videoid){
			this._playlist.videoid = 0;
		}
		if(self.preloader)
			self.preloader.stop().animate({opacity:1},0,function(){$(this).show()});

		//play next on finish check
		if(self.options.onFinish=="Play next video")
		{
			// Change title when finish video
			var title = $(self.nowPlayingTitle[0]).text();
			var nb = 0;
			while (nb < self.options.videos.length) {
				if (self.options.videos[nb].title === title) {
					var nextID = nb + 1;
					break;
				}
				nb++;
			}
			var nextTitle = self.options.videos[nextID].title;
			$('.video-title h1 b').text(nextTitle);	
			self._playlist.videoAdPlayed=false;
			if(self.shuffleBtnEnabled){
				self.setPlaylistItem(self.randEnd);
				self._playlist.videoid = self.randEnd;
			}
			else{
				self.setPlaylistItem(self._playlist.videoid);
			}
			self.playVideoById(self._playlist.videoid);

			if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5" || self.options.videoType=="HTML5 (self-hosted)")
			{
				//play next on finish
				/*if(self.shuffleBtnEnabled){
					if(this.myVideo.canPlayType && this.myVideo.canPlayType('video/mp4').replace(/no/, ''))
					{
						this.canPlay = true;
						this.video_path = self._playlist.videos_array[self.randEnd].video_path_mp4;
						this.video_pathAD = self._playlist.videos_array[self.randEnd].preroll_mp4;
					}
					this.load(self.video_path);
					this.play();

					if(self._playlist.videos_array[self.randEnd].prerollAD=="yes")
					{
						self.pause();
						self.loadAD(self.video_pathAD);
						self.openAD();
					}
					$(self.element).find(".vpl-infoTitle").html(self._playlist.videos_array[self.randEnd].title);
					$(self.element).find(".vpl-infoText").html(self._playlist.videos_array[self.randEnd].info_text);
					$(self.element).find(".vpl-nowPlayingText").html(self._playlist.videos_array[self.randEnd].title);
					
					this.loaded=false;
				}
				else{
					if(this.myVideo.canPlayType && this.myVideo.canPlayType('video/mp4').replace(/no/, ''))
					{
						this.canPlay = true;
						this.video_path = self._playlist.videos_array[self._playlist.videoid].video_path_mp4;
						this.video_pathAD = self._playlist.videos_array[self._playlist.videoid].preroll_mp4;
					}

					this.load(self.video_path);
					this.play();

					if(self._playlist.videos_array[self._playlist.videoid].prerollAD=="yes")
					{
						self.pause();
						self.loadAD(self.video_pathAD);
						self.openAD();
					}

					$(self.element).find(".vpl-infoTitle").html(self._playlist.videos_array[self._playlist.videoid].title);
					$(self.element).find(".vpl-infoText").html(self._playlist.videos_array[self._playlist.videoid].info_text);
					$(self.element).find(".vpl-nowPlayingText").html(self._playlist.videos_array[self._playlist.videoid].title);
					
					this.loaded=false;
				}*/

			}
			else if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube" || self.options.videoType=="YouTube")
			{
				/*if(self.shuffleBtnEnabled)
					this._playlist.playYoutube(this.randEnd);
				else
					this._playlist.playYoutube(this._playlist.videoid);
				this.removeHTML5elements();*/
			}
			else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo" || self.options.videoType=="Vimeo")
			{
				/*if(self.shuffleBtnEnabled){
					this._playlist.playVimeo(this._playlist.randEnd);
				}
				else{
					this._playlist.playVimeo(this.videoid);
				}
				this.removeHTML5elements();*/
			}
			if(self.shuffleBtnEnabled) {
				self.setPlaylistItem(self.randEnd);
			}
			else {
				self.setPlaylistItem(self._playlist.videoid);
			}
		}
		else if(self.options.onFinish=="Restart video")
		{
			this.resetPlayer();
			this.seek(0);
			this.play();
			this.preloader.hide();
		}
		else if(self.options.onFinish=="Stop video")
		{
			this.pause();
			this.preloader.hide();
			
			if(this.options.posterImgOnVideoFinish != ""){
				this.resetPlayer();
				this.seek(0);
				this.pause();
				
				this.showPoster2();
			}
		}
    }, this));

    this.oncanplay($.proxy(function(){
        this.canPlay = true;
        this.controls.removeClass("vpl-disabled");
    }, this));

	this.mainContainer.parent().hover(function(e){
		
	});
	
	this.mainContainer.parent().hover(function(){
		$(document).keydown($.proxy(function(e)
		{
			if (e.keyCode == 32)
			{
				// Space
				self.togglePlay();
				return false;
			}

			if (e.keyCode == 27 && this.inFullScreen)
			{
				//escape
				self.fullScreen(!this.inFullScreen);
			}
		}, this));
    },function(){
		$(document).unbind('keydown');
    });
};
window.Video = Video;
})(jQuery);