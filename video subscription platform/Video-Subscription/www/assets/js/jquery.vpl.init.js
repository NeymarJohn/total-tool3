$(document).ready(function () {

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

    var isMobile = getMobileOperatingSystem();
    
    if ($('#video-player').data('type')) {
        // Playlist
        var videoType = $('#video-player').data('type');
        var title = $('#video-player').data('title');
        var youtubeID = $('#video-player').data('youtubeid');
        var vimeoID = $('#video-player').data('vimeoid');
        var mp4 = $('#video-player').data('src');
        var imageUrl = $('#video-player').data('imageurl');
        var description = $('#video-player').data('description');
        var thumbImg = $('#video-player').data('imageurl');

        var arrayVideoType = videoType.split('|');
        var arrayTitle = title.split('|');
        var arrayYoutubeID = youtubeID.split('|');
        var arrayVimeoID = vimeoID.split('|');
        var arrayMp4 = mp4.split('|');
        var arrayImageUrl = imageUrl.split('|');
        var arrayDescription = description.split('|');
        var arrayThumbImg = thumbImg.split('|');

        var playlist = [];
        for (var i = 0; i < arrayVideoType.length; i++) {
            var element = {};
            element.videoType = arrayVideoType[i];
            element.title = arrayTitle[i];
            element.youtubeID = arrayYoutubeID[i];
            element.vimeoID = arrayVimeoID[i];
            element.mp4 = arrayMp4[i];
            element.enable_mp4_download = "no";
            element.imageUrl = arrayImageUrl[i];
            element.imageTimer = 5;
            element.prerollAD = $('#video-player').data('ads');
            element.prerollGotoLink = $('#video-player').data('adslink');
            element.preroll_mp4 = $('#video-player').data('adsvideo');
            element.prerollSkipTimer = $('#video-player').data('adsduration');
            element.midrollAD = "no";
            element.postrollAD = "no";
            element.popupAdShow = "no";
            element.description = arrayDescription[i];
            element.thumbImg = arrayThumbImg[i];
            playlist.push(element);
        }

        // Configuration
        videoPlayer = $("#video-player").Video({
            instanceName: "videosubscription", //name of the player instance
            instanceTheme: "dark", //choose video player theme: "dark", "light"
            autohideControls: (isMobile) ? 240 : 5, //autohide HTML5 player controls
            hideControlsOnMouseOut: "No", //hide HTML5 player controls on mouse out of the player: "Yes","No"
            playerLayout: "fitToContainer", //Select player layout: "fitToContainer" (responsive mode), "fixedSize" (fixed mode), "fitToBrowser" (fill the browser mode)
            videoRatio: 16 / 9, //set your video ratio (calculate video width/video height)
            lightBox: false, //lightbox mode :true/false
            playlist: "Right playlist", //choose playlist type: "Right playlist", "Bottom playlist", "Off"
            playlistScrollType: "minimal-dark", //choose scrollbar type: "light","minimal","light-2","light-3","light-thick","light-thin","inset","inset-2","inset-3","rounded","rounded-dots","3d","dark","minimal-dark","dark-2","dark-3","dark-thick","dark-thin","inset-dark","inset-2-dark","inset-3-dark","rounded-dark","rounded-dots-dark","3d-dark","3d-thick-dark"
            playlistBehaviourOnPageload: "closed",
            autoplay: false, //autoplay when webpage loads: true/false
            colorAccent: $('#video-player').data('color'), //plugin colors accent (hexadecimal or RGB value - http://www.colorpicker.com/)
            vimeoColor: "00adef", //set "hexadecimal value", default vimeo color is "00adef"
            youtubeControls: "custom controls", //choose youtube player controls: "custom controls", "default controls"
            youtubeSkin: "dark", //default youtube controls theme: light, dark
            youtubeColor: "red", //default youtube controls bar color: red, white
            youtubeQuality: "default", //choose youtube quality: "small", "medium", "large", "hd720", "hd1080", "highres", "default"
            youtubeShowRelatedVideos: "Yes", //choose to show youtube related videos when video finish: "Yes", "No" (onFinish:"Stop video" needs to be enabled)
            videoPlayerShadow: "effect1", //choose player shadow:  "effect1" , "effect2", "effect3", "effect4", "effect5", "effect6", "off"
            loadRandomVideoOnStart: "No", //choose to load random video when webpage loads: "Yes", "No"
            shuffle: "No", //choose to shuffle videos when playing one after another: "Yes", "No" (shuffle button enabled/disabled on start)
            posterImg: $('#video-player').data('cover'),
            posterImgOnVideoFinish: $('#video-player').data('cover'),
            onFinish: "Play next video", //"Play next video","Restart video", "Stop video",
            nowPlayingText: "Yes", //enable disable now playing title: "Yes","No"
            fullscreen: "Fullscreen native", //choose fullscreen type: "Fullscreen native","Fullscreen browser"
            preloadSelfHosted: "none", //choose preload buffer for self hosted mp4 videos (video type HTML5): "none", "auto"
            rightClickMenu: false, //enable/disable right click over HTML5 player: true/false
            hideVideoSource: true, //option to hide self hosted video sources (to prevent users from download/steal your videos): true/false
            showAllControls: !isMobile, //enable/disable all HTML5 player controls: true/false
            allowSkipAd: true, //enable/disable "Skip advertisement" option: true/false
            infoShow: "No", //enable/disable info option: "Yes","No"
            shareShow: "No", //enable/disable all share options: "Yes","No"
            facebookShow: "No", //enable/disable facebook option individually: "Yes","No"
            twitterShow: "No", //enable/disable twitter option individually: "Yes","No"
            mailShow: "No", //enable/disable mail option individually: "Yes","No"
            logoShow: "No", //"Yes","No"
            logoClickable: "No", //"Yes","No"
            logoPath: "images/logo.png", //path to logo image
            logoGoToLink: "", //redirect to page when logo clicked
            logoPosition: "bottom-left", //choose logo position: "bottom-right","bottom-left"
            embedShow: "No", //enable/disable embed option: "Yes","No"
            embedCodeSrc: "", //path to your video player on server
            embedCodeW: "746", //embed player code width
            embedCodeH: "420", //embed player code height
            embedShareLink: "", //direct link to your site (or any other URL) you want to be "shared"
            showGlobalPrerollAds: false, //enable/disable 'global' ads and overwrite each individual ad in 'videos' :true/false
            globalPrerollAds: "", //set 'pool' of url's that are separated by ; (global prerolls will play randomly)
            globalPrerollAdsSkipTimer: 5, //skip global advertisement seconds
            globalPrerollAdsGotoLink: "", //global advertisement goto link
            advertisementTitle: "Advertisement", //translate "Advertisement" title to your language
            skipAdvertisementText: "Skip advertisement", //translate "Skip advertisement" button to your language
            skipAdText: "You can skip this ad in", //translate "You can skip this ad in" counter to your language
            playBtnTooltipTxt: "Play", //translate "Play" to your language
            pauseBtnTooltipTxt: "Pause", //translate "Pause" to your language
            rewindBtnTooltipTxt: "Rewind", //translate "Rewind" to your language
            qualityBtnOpenedTooltipTxt: "Close settings", //translate "Close settings" to your language
            qualityBtnClosedTooltipTxt: "Settings", //translate "Settings" to your language
            muteBtnTooltipTxt: "Mute", //translate "Mute" to your language
            unmuteBtnTooltipTxt: "Unmute", //translate "Unmute" to your language
            fullscreenBtnTooltipTxt: "Fullscreen", //translate "Fullscreen" to your language
            exitFullscreenBtnTooltipTxt: "Exit fullscreen", //translate "Exit fullscreen" to your language
            volumeTooltipTxt: "Volume", //translate "Volume" to your language
            playlistBtnClosedTooltipTxt: "Show playlist", //translate "Show playlist" to your language
            playlistBtnOpenedTooltipTxt: "Hide playlist", //translate "Exit fullscreen" to your language
            nowPlayingTooltipTxt: "NOW PLAYING", //translate "NOW PLAYING" to your language
            youtubePlaylistID: "", //automatic youtube playlist ID (leave blank "" if you want to use manual playlist) LL4qbSRobYCjvwo4FCQFrJ4g
            youtubeChannelID: "", //automatic youtube channel ID (leave blank "" if you want to use manual playlist) UCHqaLr9a9M7g9QN6xem9HcQ
            videos: playlist
        });
    }
});
