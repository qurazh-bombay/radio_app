<template>
    <div>
        <div class="row mt-5">
            <div id="running-text-container" class="col-4 offset-4">
                <h5>{{ radioSourceName }}</h5>
            </div>
        </div>
        <div class="row mt-5">
            <div class="play-container">
                <audio hidden ref="player">
                    <source :src="radioSource" type="audio/ogg; codecs=vorbis">
                </audio>
                <div class="button-container">
                    <a :class="setPlayBtnClass" @click="play"><i :class="setPlayIconClass" aria-hidden="true"></i></a>
                    <a class="stop-btn button" @click="stop"><i class="fa fa-stop" aria-hidden="true"></i></a>
                </div>
                <div class="slide-container">
					<span class="volume" @click="muteHandle">
						<i :class="setVolumeIconValue" aria-hidden="true"></i>
					</span>
                    <input
                            type="range"
                            min="0"
                            max="100"
                            class="slider"
                            id="volumeSetter"
                            v-model="sliderValue">
                    <span class="volume-render">{{ sliderValue }}</span>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

    import { eventBus } from '../eventBus/eventBus';

    export default {
        name: "RadioPlayer",
        // get cookie object
        props: {
            cookieSettings: {
                type: Object,
                default() {
                    return {
                        'source': '',
                        'sourceName': '',
                        'volume': 50,
                    };
                },
            }
        },
        data() {
            return {
                radioSourceName: '',
                radioSourceNamePrev: this.cookieSettings.sourceName,
                radioSource: '',
                radioSourcePrev: this.cookieSettings.source,
                isPlaying: false,
                sliderValue: this.cookieSettings.volume,
                sliderValueKeeper: 50,
                isMuted: !this.cookieSettings.volume,
            };
        },
        methods: {
            // set source for audio player, starts play, set cookies
            setSource(radioChannel) {
                this.radioSourcePrev = radioChannel.url;
                this.radioSourceNamePrev = radioChannel.name;
                this.sourceLoad();
                this.setCurrentVolume();
                this.playInit();
                this.setCookie('channel-source', radioChannel.id);
                this.setCookie('channel-name', radioChannel.name);
                this.setCookie('volume', this.sliderValue);
            },

            // play-pause button action
            play() {
                if (this.radioSourcePrev && !this.isPlaying) {
                    // if a stop was pressed and a source was cleared
                    if (!this.radioSource) {
                        this.sourceLoad();
                    }
                    this.setCurrentVolume();
                    this.playInit();
                } else {
                    this.pauseInit();
                }
            },

            // stop playing, cut stream loading (set empty string and load it as an url source)
            stop() {
                this.pauseInit();
                // stop a stream loading
                this.radioSource = '';
                this.radioSourceName = '';
                this.$refs.player.load();
                //this.$refs.player.currentTime = 0.0;
            },

            // get a slider value and transform it into volume level for audio tag
            setCurrentVolume() {
                let volume = this.sliderValue / 100;
                if (volume < 0 || volume > 1) {
                    volume = 0.5;
                }
                this.isMuted = !volume;
                this.$refs.player.volume = volume;
            },

            // start playing and mark flag for button (play, pause)
            playInit() {
                this.$refs.player.play();
                this.isPlaying = true;
            },

            pauseInit() {
                this.$refs.player.pause();
                this.isPlaying = false;
            },

            // set url source for audio tag and load it
            sourceLoad() {
                this.radioSourceName = this.radioSourceNamePrev;
                this.radioSource = this.radioSourcePrev;
                this.$refs.player.load();
            },

            setCookie(type, cookieValue) {
                let date = new Date(new Date().getTime() + 60 * 60 * 24 * 30 * 1000); // 30 days
                document.cookie = `${type}=${cookieValue};path=/;expires=${date.toUTCString()}`;
            },

            muteHandle() {
                if (this.isMuted) {
                    this.sliderValue = this.sliderValueKeeper;
                    this.setCurrentVolume();
                } else {
                    this.sliderValueKeeper = this.sliderValue;
                    this.sliderValue = 0;
                    this.setCurrentVolume();
                }
            }

        },
        watch: {
            // watching value of volume slider
            sliderValue() {
                this.setCurrentVolume();
                this.setCookie('volume', this.sliderValue)
            },

        },
        computed: {
            // set right class for play-pause button for correct icon rendering
            setPlayIconClass() {
                return this.isPlaying ? 'fa fa-pause' : 'fa fa-play';
            },

            // set right class for play-pause button for correct color highlight rendering
            setPlayBtnClass() {
                return this.isPlaying ? 'pause-btn button' : 'play-btn button';
            },

            //set right class for correct rendering volume icon
            setVolumeIconValue() {
                return this.isMuted ? 'fa fa-volume-off' : 'fa fa-volume-up';
            },
        },
        created() {
            console.log('radio player created');

            // gets data from RadioList component
            // and set selected channel to radio player
            eventBus.$on('setChannel', this.setSource);
        }
    }
</script>

<style scoped>
    .play-container {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    /*
    =====SLIDER=====
    */
    .slide-container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        width: 40%;
    }

    .slider {
        -webkit-appearance: none;
        width: 70%;
        height: 10px;
        border-radius: 5px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
    }

    .slider::-webkit-slider-thumb:hover {
        background: #00ab9e;
        text-shadow:0px 0px 6px #00ab9e;
    }

    .volume {
        width: 15px;
        margin-right: 30px;
        cursor: pointer;
        font-size: 20px;
    }
    .volume-render {
        margin-left: 30px;
    }

    /*
    ======BUTTONS====
    */
    .button {
        background-image: -webkit-linear-gradient(top, #f4f1ee, #fff);
        background-image: linear-gradient(top, #f4f1ee, #fff);
        border-radius: 50%;
        box-shadow: 0px 8px 10px 0px rgba(0, 0, 0, .3), inset 0px 4px 1px 1px white, inset 0px -3px 1px 1px rgba(204,198,197,.5);
        float:left;
        height: 70px;
        margin: 0 30px 0 0;
        position: relative;
        width: 70px;
        -webkit-transition: all .1s linear;
        transition: all .1s linear;
        line-height: 70px;
        text-align: center;
    }

    .button:after{
        color:#e9e6e4;
        content: "";
        display: block;
        font-size: 30px;
        height: 30px;
        text-decoration: none;
        text-shadow: 0px -1px 1px #bdb5b4, 1px 1px 1px white;
        position: absolute;
        width: 30px;
    }

    .button:hover{
        background-image: -webkit-linear-gradient(top, #fff, #f4f1ee);
        background-image: linear-gradient(top, #fff, #f4f1ee);
        color:#0088cc;
        cursor: pointer;
    }

    .button:active{
        background-image: -webkit-linear-gradient(top, #efedec, #f7f4f4);
        background-image: linear-gradient(top, #efedec, #f7f4f4);
        box-shadow: 0 3px 5px 0 rgba(0,0,0,.4), inset 0px -3px 1px 1px rgba(204,198,197,.5);
    }

    .button:active:after{
        color:#dbd2d2;
        text-shadow: 0px -1px 1px #bdb5b4, 0px 1px 1px white;
    }

    .play-btn i,
    .pause-btn i,
    .stop-btn i {
        color: #007bff;
        text-shadow:0px 0px 6px #007bff;
    }

    .play-btn:hover i {
        color: #00ab9e;
        text-shadow:0px 0px 6px #00ab9e;
    }

    .pause-btn:hover i {
        color: #f99e4e;
        text-shadow:0px 0px 6px #f99e4e;
    }

    .stop-btn:hover i {
        color: #ff0040;
        text-shadow:0px 0px 6px #ff0040;
    }

    /*
    ======RUNNING TEXT====
    */
    #running-text-container {
        height: 25px;
        overflow: hidden;
        position: relative;
    }

    #running-text-container h5 {
        text-transform: capitalize;
        font-size: 1.5em;
        color: #007bff;
        position: absolute;
        width: 100%;
        height: 100%;
        margin: 0;
        line-height: 25px;
        text-align: center;
        /* Starting position */
        -moz-transform:translateX(100%);
        -webkit-transform:translateX(100%);
        transform:translateX(100%);
        /* Apply animation to this element */
        -moz-animation: running-text-container 5s linear infinite;
        -webkit-animation: running-text-container 5s linear infinite;
        animation: running-text-container 5s linear infinite;
    }

    /* Move it (define the animation) */
    @-moz-keyframes running-text-container {
        0%   { -moz-transform: translateX(100%); }
        100% { -moz-transform: translateX(-100%); }
    }

    @-webkit-keyframes running-text-container {
        0%   { -webkit-transform: translateX(100%); }
        100% { -webkit-transform: translateX(-100%); }
    }

    @keyframes running-text-container {
        0%   {
            -moz-transform: translateX(100%); /* Firefox bug fix */
            -webkit-transform: translateX(100%); /* Firefox bug fix */
            transform: translateX(100%);
        }
        100% {
            -moz-transform: translateX(-100%); /* Firefox bug fix */
            -webkit-transform: translateX(-100%); /* Firefox bug fix */
            transform: translateX(-100%);
        }
    }
</style>
