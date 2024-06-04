<style>
    .marvel-device {
    display: inline-block;
    position: relative;
    -webkit-box-sizing: content-box !important;
    box-sizing: content-box !important
}

.marvel-device .screen {
    width: 100%;
    position: relative;
    height: 100%;
    z-index: 3;
    /*background: white;*/
    /*background: linear-gradient(to bottom, #ff268e 0%, #ff694f 100%);*/
    /*background-image: url();*/
    /*background-image: url(https://i.pinimg.com/736x/8d/ca/c6/8dcac69f91250eb3f28f3055ef03d5dd.jpg);*/
    background-size: cover;
    overflow: hidden;
    display: block;
    border-radius: 1px;
    -webkit-box-shadow: 0 0 0 3px #111;
    box-shadow: 0 0 0 3px #111
}

.marvel-device .top-bar,
.marvel-device .bottom-bar {
    height: 3px;
    background: black;
    width: 100%;
    display: block
}

.marvel-device .middle-bar {
    width: 3px;
    height: 4px;
    top: 0px;
    left: 90px;
    background: black;
    position: absolute
}

.marvel-device.iphone8 {
    width: 375px;
    height: 667px;
    padding: 105px 24px;
    background: #d9dbdc;
    border-radius: 56px;
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.2);
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.2)
}

.marvel-device.iphone8:before {
    width: calc(100% - 12px);
    height: calc(100% - 12px);
    position: absolute;
    top: 6px;
    content: '';
    left: 6px;
    border-radius: 50px;
    background: #f8f8f8;
    z-index: 1
}

.marvel-device.iphone8:after {
    width: calc(100% - 16px);
    height: calc(100% - 16px);
    position: absolute;
    top: 8px;
    content: '';
    left: 8px;
    border-radius: 48px;
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #fff;
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #fff;
    z-index: 2
}

.marvel-device.iphone8 .home {
    border-radius: 100%;
    width: 68px;
    height: 68px;
    position: absolute;
    left: 50%;
    margin-left: -34px;
    bottom: 22px;
    z-index: 3;
    background: #303233;
    background: linear-gradient(135deg, #303233 0%, #b5b7b9 50%, #f0f2f2 69%, #303233 100%)
}

.marvel-device.iphone8 .home:before {
    background: #f8f8f8;
    position: absolute;
    content: '';
    border-radius: 100%;
    width: calc(100% - 8px);
    height: calc(100% - 8px);
    top: 4px;
    left: 4px
}

.marvel-device.iphone8 .top-bar {
    height: 14px;
    background: #bfbfc0;
    position: absolute;
    top: 68px;
    left: 0
}

.marvel-device.iphone8 .bottom-bar {
    height: 14px;
    background: #bfbfc0;
    position: absolute;
    bottom: 68px;
    left: 0
}

.marvel-device.iphone8 .sleep {
    position: absolute;
    top: 190px;
    right: -4px;
    width: 4px;
    height: 66px;
    border-radius: 0px 2px 2px 0px;
    background: #d9dbdc
}

.marvel-device.iphone8 .volume {
    position: absolute;
    left: -4px;
    top: 188px;
    z-index: 0;
    height: 66px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: #d9dbdc
}

.marvel-device.iphone8 .volume:before {
    position: absolute;
    left: 2px;
    top: -78px;
    height: 40px;
    width: 2px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone8 .volume:after {
    position: absolute;
    left: 0px;
    top: 82px;
    height: 66px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone8 .camera {
    background: #3c3d3d;
    width: 12px;
    height: 12px;
    position: absolute;
    top: 24px;
    left: 50%;
    margin-left: -6px;
    border-radius: 100%;
    z-index: 3
}

.marvel-device.iphone8 .sensor {
    background: #3c3d3d;
    width: 16px;
    height: 16px;
    position: absolute;
    top: 49px;
    left: 134px;
    z-index: 3;
    border-radius: 100%
}

.marvel-device.iphone8 .speaker {
    background: #292728;
    width: 70px;
    height: 6px;
    position: absolute;
    top: 54px;
    left: 50%;
    margin-left: -35px;
    border-radius: 6px;
    z-index: 3
}

.marvel-device.iphone8.gold {
    background: #f9e7d3
}

.marvel-device.iphone8.gold .top-bar,
.marvel-device.iphone8.gold .bottom-bar {
    background: white
}

.marvel-device.iphone8.gold .sleep,
.marvel-device.iphone8.gold .volume {
    background: #f9e7d3
}

.marvel-device.iphone8.gold .home {
    background: #cebba9;
    background: linear-gradient(135deg, #cebba9 0%, #f9e7d3 50%, #cebba9 100%)
}

.marvel-device.iphone8.black {
    background: #464646;
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.7);
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.7)
}

.marvel-device.iphone8.black:before {
    background: #080808
}

.marvel-device.iphone8.black:after {
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #212121;
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #212121
}

.marvel-device.iphone8.black .top-bar,
.marvel-device.iphone8.black .bottom-bar {
    background: #212121
}

.marvel-device.iphone8.black .volume,
.marvel-device.iphone8.black .sleep {
    background: #464646
}

.marvel-device.iphone8.black .camera {
    background: #080808
}

.marvel-device.iphone8.black .home {
    background: #080808;
    background: linear-gradient(135deg, #080808 0%, #464646 50%, #080808 100%)
}

.marvel-device.iphone8.black .home:before {
    background: #080808
}

.marvel-device.iphone8.landscape {
    padding: 24px 105px;
    height: 375px;
    width: 667px
}

.marvel-device.iphone8.landscape .sleep {
    top: 100%;
    border-radius: 0px 0px 2px 2px;
    right: 190px;
    height: 4px;
    width: 66px
}

.marvel-device.iphone8.landscape .volume {
    width: 66px;
    height: 4px;
    top: -4px;
    left: calc(100% - 188px - 66px);
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone8.landscape .volume:before {
    width: 40px;
    height: 2px;
    top: 2px;
    right: -78px;
    left: auto;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone8.landscape .volume:after {
    left: -82px;
    width: 66px;
    height: 4px;
    top: 0;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone8.landscape .top-bar {
    width: 14px;
    height: 100%;
    left: calc(100% - 68px - 14px);
    top: 0
}

.marvel-device.iphone8.landscape .bottom-bar {
    width: 14px;
    height: 100%;
    left: 68px;
    top: 0
}

.marvel-device.iphone8.landscape .home {
    top: 50%;
    margin-top: -34px;
    margin-left: 0;
    left: 22px
}

.marvel-device.iphone8.landscape .sensor {
    top: 134px;
    left: calc(100% - 49px - 16px)
}

.marvel-device.iphone8.landscape .speaker {
    height: 70px;
    width: 6px;
    left: calc(100% - 54px - 6px);
    top: 50%;
    margin-left: 0px;
    margin-top: -35px
}

.marvel-device.iphone8.landscape .camera {
    left: calc(100% - 32px);
    top: 50%;
    margin-left: 0px;
    margin-top: -5px
}

.marvel-device.iphone8plus {
    width: 414px;
    height: 736px;
    padding: 112px 26px;
    background: #d9dbdc;
    border-radius: 56px;
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.2);
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.2)
}

.marvel-device.iphone8plus:before {
    width: calc(100% - 12px);
    height: calc(100% - 12px);
    position: absolute;
    top: 6px;
    content: '';
    left: 6px;
    border-radius: 50px;
    background: #f8f8f8;
    z-index: 1
}

.marvel-device.iphone8plus:after {
    width: calc(100% - 16px);
    height: calc(100% - 16px);
    position: absolute;
    top: 8px;
    content: '';
    left: 8px;
    border-radius: 48px;
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #fff;
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #fff;
    z-index: 2
}

.marvel-device.iphone8plus .home {
    border-radius: 100%;
    width: 68px;
    height: 68px;
    position: absolute;
    left: 50%;
    margin-left: -34px;
    bottom: 24px;
    z-index: 3;
    background: #303233;
    background: linear-gradient(135deg, #303233 0%, #b5b7b9 50%, #f0f2f2 69%, #303233 100%)
}

.marvel-device.iphone8plus .home:before {
    background: #f8f8f8;
    position: absolute;
    content: '';
    border-radius: 100%;
    width: calc(100% - 8px);
    height: calc(100% - 8px);
    top: 4px;
    left: 4px
}

.marvel-device.iphone8plus .top-bar {
    height: 14px;
    background: #bfbfc0;
    position: absolute;
    top: 68px;
    left: 0
}

.marvel-device.iphone8plus .bottom-bar {
    height: 14px;
    background: #bfbfc0;
    position: absolute;
    bottom: 68px;
    left: 0
}

.marvel-device.iphone8plus .sleep {
    position: absolute;
    top: 190px;
    right: -4px;
    width: 4px;
    height: 66px;
    border-radius: 0px 2px 2px 0px;
    background: #d9dbdc
}

.marvel-device.iphone8plus .volume {
    position: absolute;
    left: -4px;
    top: 188px;
    z-index: 0;
    height: 66px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: #d9dbdc
}

.marvel-device.iphone8plus .volume:before {
    position: absolute;
    left: 2px;
    top: -78px;
    height: 40px;
    width: 2px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone8plus .volume:after {
    position: absolute;
    left: 0px;
    top: 82px;
    height: 66px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone8plus .camera {
    background: #3c3d3d;
    width: 12px;
    height: 12px;
    position: absolute;
    top: 29px;
    left: 50%;
    margin-left: -6px;
    border-radius: 100%;
    z-index: 3
}

.marvel-device.iphone8plus .sensor {
    background: #3c3d3d;
    width: 16px;
    height: 16px;
    position: absolute;
    top: 54px;
    left: 154px;
    z-index: 3;
    border-radius: 100%
}

.marvel-device.iphone8plus .speaker {
    background: #292728;
    width: 70px;
    height: 6px;
    position: absolute;
    top: 59px;
    left: 50%;
    margin-left: -35px;
    border-radius: 6px;
    z-index: 3
}

.marvel-device.iphone8plus.gold {
    background: #f9e7d3
}

.marvel-device.iphone8plus.gold .top-bar,
.marvel-device.iphone8plus.gold .bottom-bar {
    background: white
}

.marvel-device.iphone8plus.gold .sleep,
.marvel-device.iphone8plus.gold .volume {
    background: #f9e7d3
}

.marvel-device.iphone8plus.gold .home {
    background: #cebba9;
    background: linear-gradient(135deg, #cebba9 0%, #f9e7d3 50%, #cebba9 100%)
}

.marvel-device.iphone8plus.black {
    background: #464646;
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.7);
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.7)
}

.marvel-device.iphone8plus.black:before {
    background: #080808
}

.marvel-device.iphone8plus.black:after {
    -webkit-box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #212121;
    box-shadow: inset 0 0 3px 0 rgba(0, 0, 0, 0.1), inset 0 0 6px 3px #212121
}

.marvel-device.iphone8plus.black .top-bar,
.marvel-device.iphone8plus.black .bottom-bar {
    background: #212121
}

.marvel-device.iphone8plus.black .volume,
.marvel-device.iphone8plus.black .sleep {
    background: #464646
}

.marvel-device.iphone8plus.black .camera {
    background: #080808
}

.marvel-device.iphone8plus.black .home {
    background: #080808;
    background: linear-gradient(135deg, #080808 0%, #464646 50%, #080808 100%)
}

.marvel-device.iphone8plus.black .home:before {
    background: #080808
}

.marvel-device.iphone8plus.landscape {
    padding: 26px 112px;
    height: 414px;
    width: 736px
}

.marvel-device.iphone8plus.landscape .sleep {
    top: 100%;
    border-radius: 0px 0px 2px 2px;
    right: 190px;
    height: 4px;
    width: 66px
}

.marvel-device.iphone8plus.landscape .volume {
    width: 66px;
    height: 4px;
    top: -4px;
    left: calc(100% - 188px - 66px);
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone8plus.landscape .volume:before {
    width: 40px;
    height: 2px;
    top: 2px;
    right: -78px;
    left: auto;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone8plus.landscape .volume:after {
    left: -82px;
    width: 66px;
    height: 4px;
    top: 0;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone8plus.landscape .top-bar {
    width: 14px;
    height: 100%;
    left: calc(100% - 68px - 14px);
    top: 0
}

.marvel-device.iphone8plus.landscape .bottom-bar {
    width: 14px;
    height: 100%;
    left: 68px;
    top: 0
}

.marvel-device.iphone8plus.landscape .home {
    top: 50%;
    margin-top: -34px;
    margin-left: 0;
    left: 24px
}

.marvel-device.iphone8plus.landscape .sensor {
    top: 154px;
    left: calc(100% - 54px - 16px)
}

.marvel-device.iphone8plus.landscape .speaker {
    height: 70px;
    width: 6px;
    left: calc(100% - 59px - 6px);
    top: 50%;
    margin-left: 0px;
    margin-top: -35px
}

.marvel-device.iphone8plus.landscape .camera {
    left: calc(100% - 29px);
    top: 50%;
    margin-left: 0px;
    margin-top: -5px
}

.marvel-device.iphone5s,
.marvel-device.iphone5c {
    padding: 105px 22px;
    background: #2c2b2c;
    width: 320px;
    height: 568px;
    border-radius: 50px
}

.marvel-device.iphone5s:before,
.marvel-device.iphone5c:before {
    width: calc(100% - 8px);
    height: calc(100% - 8px);
    position: absolute;
    top: 4px;
    content: '';
    left: 4px;
    border-radius: 46px;
    background: #1e1e1e;
    z-index: 1
}

.marvel-device.iphone5s .sleep,
.marvel-device.iphone5c .sleep {
    position: absolute;
    top: -4px;
    right: 60px;
    width: 60px;
    height: 4px;
    border-radius: 2px 2px 0px 0px;
    background: #282727
}

.marvel-device.iphone5s .volume,
.marvel-device.iphone5c .volume {
    position: absolute;
    left: -4px;
    top: 180px;
    z-index: 0;
    height: 27px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: #282727
}

.marvel-device.iphone5s .volume:before,
.marvel-device.iphone5c .volume:before {
    position: absolute;
    left: 0px;
    top: -75px;
    height: 35px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone5s .volume:after,
.marvel-device.iphone5c .volume:after {
    position: absolute;
    left: 0px;
    bottom: -64px;
    height: 27px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone5s .camera,
.marvel-device.iphone5c .camera {
    background: #3c3d3d;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 32px;
    left: 50%;
    margin-left: -5px;
    border-radius: 5px;
    z-index: 3
}

.marvel-device.iphone5s .sensor,
.marvel-device.iphone5c .sensor {
    background: #3c3d3d;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 60px;
    left: 160px;
    z-index: 3;
    margin-left: -32px;
    border-radius: 5px
}

.marvel-device.iphone5s .speaker,
.marvel-device.iphone5c .speaker {
    background: #292728;
    width: 64px;
    height: 10px;
    position: absolute;
    top: 60px;
    left: 50%;
    margin-left: -32px;
    border-radius: 5px;
    z-index: 3
}

.marvel-device.iphone5s.landscape,
.marvel-device.iphone5c.landscape {
    padding: 22px 105px;
    height: 320px;
    width: 568px
}

.marvel-device.iphone5s.landscape .sleep,
.marvel-device.iphone5c.landscape .sleep {
    right: -4px;
    top: calc(100% - 120px);
    height: 60px;
    width: 4px;
    border-radius: 0px 2px 2px 0px
}

.marvel-device.iphone5s.landscape .volume,
.marvel-device.iphone5c.landscape .volume {
    width: 27px;
    height: 4px;
    top: -4px;
    left: calc(100% - 180px);
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone5s.landscape .volume:before,
.marvel-device.iphone5c.landscape .volume:before {
    width: 35px;
    height: 4px;
    top: 0px;
    right: -75px;
    left: auto;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone5s.landscape .volume:after,
.marvel-device.iphone5c.landscape .volume:after {
    bottom: 0px;
    left: -64px;
    z-index: 999;
    height: 4px;
    width: 27px;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone5s.landscape .sensor,
.marvel-device.iphone5c.landscape .sensor {
    top: 160px;
    left: calc(100% - 60px);
    margin-left: 0px;
    margin-top: -32px
}

.marvel-device.iphone5s.landscape .speaker,
.marvel-device.iphone5c.landscape .speaker {
    height: 64px;
    width: 10px;
    left: calc(100% - 60px);
    top: 50%;
    margin-left: 0px;
    margin-top: -32px
}

.marvel-device.iphone5s.landscape .camera,
.marvel-device.iphone5c.landscape .camera {
    left: calc(100% - 32px);
    top: 50%;
    margin-left: 0px;
    margin-top: -5px
}

.marvel-device.iphone5s .home {
    border-radius: 36px;
    width: 68px;
    -webkit-box-shadow: inset 0 0 0 4px #2c2b2c;
    box-shadow: inset 0 0 0 4px #2c2b2c;
    height: 68px;
    position: absolute;
    left: 50%;
    margin-left: -34px;
    bottom: 19px;
    z-index: 3
}

.marvel-device.iphone5s .top-bar {
    top: 70px;
    position: absolute;
    left: 0
}

.marvel-device.iphone5s .bottom-bar {
    bottom: 70px;
    position: absolute;
    left: 0
}

.marvel-device.iphone5s.landscape .home {
    left: 19px;
    bottom: 50%;
    margin-bottom: -34px;
    margin-left: 0px
}

.marvel-device.iphone5s.landscape .top-bar {
    left: 70px;
    top: 0px;
    width: 3px;
    height: 100%
}

.marvel-device.iphone5s.landscape .bottom-bar {
    right: 70px;
    left: auto;
    bottom: 0px;
    width: 3px;
    height: 100%
}

.marvel-device.iphone5s.silver {
    background: #bcbcbc
}

.marvel-device.iphone5s.silver:before {
    background: #fcfcfc
}

.marvel-device.iphone5s.silver .volume,
.marvel-device.iphone5s.silver .sleep {
    background: #d6d6d6
}

.marvel-device.iphone5s.silver .top-bar,
.marvel-device.iphone5s.silver .bottom-bar {
    background: #eaebec
}

.marvel-device.iphone5s.silver .home {
    -webkit-box-shadow: inset 0 0 0 4px #bcbcbc;
    box-shadow: inset 0 0 0 4px #bcbcbc
}

.marvel-device.iphone5s.gold {
    background: #f9e7d3
}

.marvel-device.iphone5s.gold:before {
    background: #fcfcfc
}

.marvel-device.iphone5s.gold .volume,
.marvel-device.iphone5s.gold .sleep {
    background: #f9e7d3
}

.marvel-device.iphone5s.gold .top-bar,
.marvel-device.iphone5s.gold .bottom-bar {
    background: white
}

.marvel-device.iphone5s.gold .home {
    -webkit-box-shadow: inset 0 0 0 4px #f9e7d3;
    box-shadow: inset 0 0 0 4px #f9e7d3
}

.marvel-device.iphone5c {
    background: white;
    -webkit-box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2)
}

.marvel-device.iphone5c .top-bar,
.marvel-device.iphone5c .bottom-bar {
    display: none
}

.marvel-device.iphone5c .home {
    background: #242324;
    border-radius: 36px;
    width: 68px;
    height: 68px;
    z-index: 3;
    position: absolute;
    left: 50%;
    margin-left: -34px;
    bottom: 19px
}

.marvel-device.iphone5c .home:after {
    width: 20px;
    height: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    position: absolute;
    display: block;
    content: '';
    top: 50%;
    left: 50%;
    margin-top: -11px;
    margin-left: -11px
}

.marvel-device.iphone5c.landscape .home {
    left: 19px;
    bottom: 50%;
    margin-bottom: -34px;
    margin-left: 0px
}

.marvel-device.iphone5c .volume,
.marvel-device.iphone5c .sleep {
    background: #dddddd
}

.marvel-device.iphone5c.red {
    background: #f96b6c
}

.marvel-device.iphone5c.red .volume,
.marvel-device.iphone5c.red .sleep {
    background: #ed5758
}

.marvel-device.iphone5c.yellow {
    background: #f2dc60
}

.marvel-device.iphone5c.yellow .volume,
.marvel-device.iphone5c.yellow .sleep {
    background: #e5ce4c
}

.marvel-device.iphone5c.green {
    background: #97e563
}

.marvel-device.iphone5c.green .volume,
.marvel-device.iphone5c.green .sleep {
    background: #85d94d
}

.marvel-device.iphone5c.blue {
    background: #33a2db
}

.marvel-device.iphone5c.blue .volume,
.marvel-device.iphone5c.blue .sleep {
    background: #2694cd
}

.marvel-device.iphone4s {
    padding: 129px 27px;
    width: 320px;
    height: 480px;
    background: #686868;
    border-radius: 54px
}

.marvel-device.iphone4s:before {
    content: '';
    width: calc(100% - 8px);
    height: calc(100% - 8px);
    position: absolute;
    top: 4px;
    left: 4px;
    z-index: 1;
    border-radius: 50px;
    background: #1e1e1e
}

.marvel-device.iphone4s .top-bar {
    top: 60px;
    position: absolute;
    left: 0
}

.marvel-device.iphone4s .bottom-bar {
    bottom: 90px;
    position: absolute;
    left: 0
}

.marvel-device.iphone4s .camera {
    background: #3c3d3d;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 72px;
    left: 134px;
    z-index: 3;
    margin-left: -5px;
    border-radius: 100%
}

.marvel-device.iphone4s .speaker {
    background: #292728;
    width: 64px;
    height: 10px;
    position: absolute;
    top: 72px;
    left: 50%;
    z-index: 3;
    margin-left: -32px;
    border-radius: 5px
}

.marvel-device.iphone4s .sensor {
    background: #292728;
    width: 40px;
    height: 10px;
    position: absolute;
    top: 36px;
    left: 50%;
    z-index: 3;
    margin-left: -20px;
    border-radius: 5px
}

.marvel-device.iphone4s .home {
    background: #242324;
    border-radius: 100%;
    width: 72px;
    height: 72px;
    z-index: 3;
    position: absolute;
    left: 50%;
    margin-left: -36px;
    bottom: 30px
}

.marvel-device.iphone4s .home:after {
    width: 20px;
    height: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    position: absolute;
    display: block;
    content: '';
    top: 50%;
    left: 50%;
    margin-top: -11px;
    margin-left: -11px
}

.marvel-device.iphone4s .sleep {
    position: absolute;
    top: -4px;
    right: 60px;
    width: 60px;
    height: 4px;
    border-radius: 2px 2px 0px 0px;
    background: #4D4D4D
}

.marvel-device.iphone4s .volume {
    position: absolute;
    left: -4px;
    top: 160px;
    height: 27px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: #4D4D4D
}

.marvel-device.iphone4s .volume:before {
    position: absolute;
    left: 0px;
    top: -70px;
    height: 35px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone4s .volume:after {
    position: absolute;
    left: 0px;
    bottom: -64px;
    height: 27px;
    width: 4px;
    border-radius: 2px 0px 0px 2px;
    background: inherit;
    content: '';
    display: block
}

.marvel-device.iphone4s.landscape {
    padding: 27px 129px;
    height: 320px;
    width: 480px
}

.marvel-device.iphone4s.landscape .bottom-bar {
    left: 90px;
    bottom: 0px;
    height: 100%;
    width: 3px
}

.marvel-device.iphone4s.landscape .top-bar {
    left: calc(100% - 60px);
    top: 0px;
    height: 100%;
    width: 3px
}

.marvel-device.iphone4s.landscape .camera {
    top: 134px;
    left: calc(100% - 72px);
    margin-left: 0
}

.marvel-device.iphone4s.landscape .speaker {
    top: 50%;
    margin-left: 0;
    margin-top: -32px;
    left: calc(100% - 72px);
    width: 10px;
    height: 64px
}

.marvel-device.iphone4s.landscape .sensor {
    height: 40px;
    width: 10px;
    left: calc(100% - 36px);
    top: 50%;
    margin-left: 0;
    margin-top: -20px
}

.marvel-device.iphone4s.landscape .home {
    left: 30px;
    bottom: 50%;
    margin-left: 0;
    margin-bottom: -36px
}

.marvel-device.iphone4s.landscape .sleep {
    height: 60px;
    width: 4px;
    right: -4px;
    top: calc(100% - 120px);
    border-radius: 0px 2px 2px 0px
}

.marvel-device.iphone4s.landscape .volume {
    top: -4px;
    left: calc(100% - 187px);
    height: 4px;
    width: 27px;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone4s.landscape .volume:before {
    right: -70px;
    left: auto;
    top: 0px;
    width: 35px;
    height: 4px;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone4s.landscape .volume:after {
    width: 27px;
    height: 4px;
    bottom: 0px;
    left: -64px;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.iphone4s.silver {
    background: #bcbcbc
}

.marvel-device.iphone4s.silver:before {
    background: #fcfcfc
}

.marvel-device.iphone4s.silver .home {
    background: #fcfcfc;
    -webkit-box-shadow: inset 0 0 0 1px #bcbcbc;
    box-shadow: inset 0 0 0 1px #bcbcbc
}

.marvel-device.iphone4s.silver .home:after {
    border: 1px solid rgba(0, 0, 0, 0.2)
}

.marvel-device.iphone4s.silver .volume,
.marvel-device.iphone4s.silver .sleep {
    background: #d6d6d6
}

.marvel-device.nexus5 {
    padding: 50px 15px 50px 15px;
    width: 320px;
    height: 568px;
    background: #1e1e1e;
    border-radius: 20px
}

.marvel-device.nexus5:before {
    border-radius: 600px / 50px;
    background: inherit;
    content: '';
    top: 0;
    position: absolute;
    height: 103.1%;
    width: calc(100% - 26px);
    top: 50%;
    left: 50%;
    -webkit-transform: translateX(-50%) translateY(-50%);
    transform: translateX(-50%) translateY(-50%)
}

.marvel-device.nexus5 .top-bar {
    width: calc(100% - 8px);
    height: calc(100% - 6px);
    position: absolute;
    top: 3px;
    left: 4px;
    border-radius: 20px;
    background: #181818
}

.marvel-device.nexus5 .top-bar:before {
    border-radius: 600px / 50px;
    background: inherit;
    content: '';
    top: 0;
    position: absolute;
    height: 103.0%;
    width: calc(100% - 26px);
    top: 50%;
    left: 50%;
    -webkit-transform: translateX(-50%) translateY(-50%);
    transform: translateX(-50%) translateY(-50%)
}

.marvel-device.nexus5 .bottom-bar {
    display: none
}

.marvel-device.nexus5 .sleep {
    width: 3px;
    position: absolute;
    left: -3px;
    top: 110px;
    height: 100px;
    background: inherit;
    border-radius: 2px 0px 0px 2px
}

.marvel-device.nexus5 .volume {
    width: 3px;
    position: absolute;
    right: -3px;
    top: 70px;
    height: 45px;
    background: inherit;
    border-radius: 0px 2px 2px 0px
}

.marvel-device.nexus5 .camera {
    background: #3c3d3d;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 18px;
    left: 50%;
    z-index: 3;
    margin-left: -5px;
    border-radius: 100%
}

.marvel-device.nexus5 .camera:before {
    background: #3c3d3d;
    width: 6px;
    height: 6px;
    content: '';
    display: block;
    position: absolute;
    top: 2px;
    left: -100px;
    z-index: 3;
    border-radius: 100%
}

.marvel-device.nexus5.landscape {
    padding: 15px 50px 15px 50px;
    height: 320px;
    width: 568px
}

.marvel-device.nexus5.landscape:before {
    width: 103.1%;
    height: calc(100% - 26px);
    border-radius: 50px / 600px
}

.marvel-device.nexus5.landscape .top-bar {
    left: 3px;
    top: 4px;
    height: calc(100% - 8px);
    width: calc(100% - 6px)
}

.marvel-device.nexus5.landscape .top-bar:before {
    width: 103%;
    height: calc(100% - 26px);
    border-radius: 50px / 600px
}

.marvel-device.nexus5.landscape .sleep {
    height: 3px;
    width: 100px;
    left: calc(100% - 210px);
    top: -3px;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.nexus5.landscape .volume {
    height: 3px;
    width: 45px;
    right: 70px;
    top: 100%;
    border-radius: 0px 0px 2px 2px
}

.marvel-device.nexus5.landscape .camera {
    top: 50%;
    left: calc(100% - 18px);
    margin-left: 0;
    margin-top: -5px
}

.marvel-device.nexus5.landscape .camera:before {
    top: -100px;
    left: 2px
}

.marvel-device.s5 {
    padding: 60px 18px;
    border-radius: 42px;
    width: 320px;
    height: 568px;
    background: #bcbcbc
}

.marvel-device.s5:before,
.marvel-device.s5:after {
    width: calc(100% - 52px);
    content: '';
    display: block;
    height: 26px;
    background: inherit;
    position: absolute;
    border-radius: 500px / 40px;
    left: 50%;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%)
}

.marvel-device.s5:before {
    top: -7px
}

.marvel-device.s5:after {
    bottom: -7px
}

.marvel-device.s5 .bottom-bar {
    display: none
}

.marvel-device.s5 .top-bar {
    border-radius: 37px;
    width: calc(100% - 10px);
    height: calc(100% - 10px);
    top: 5px;
    left: 5px;
    background: radial-gradient(rgba(0, 0, 0, 0.02) 20%, transparent 60%) 0 0, radial-gradient(rgba(0, 0, 0, 0.02) 20%, transparent 60%) 3px 3px;
    background-color: white;
    background-size: 4px 4px;
    background-position: center;
    z-index: 2;
    position: absolute
}

.marvel-device.s5 .top-bar:before,
.marvel-device.s5 .top-bar:after {
    width: calc(100% - 48px);
    content: '';
    display: block;
    height: 26px;
    background: inherit;
    position: absolute;
    border-radius: 500px / 40px;
    left: 50%;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%)
}

.marvel-device.s5 .top-bar:before {
    top: -7px
}

.marvel-device.s5 .top-bar:after {
    bottom: -7px
}

.marvel-device.s5 .sleep {
    width: 3px;
    position: absolute;
    left: -3px;
    top: 100px;
    height: 100px;
    background: #cecece;
    border-radius: 2px 0px 0px 2px
}

.marvel-device.s5 .speaker {
    width: 68px;
    height: 8px;
    position: absolute;
    top: 20px;
    display: block;
    z-index: 3;
    left: 50%;
    margin-left: -34px;
    background-color: #bcbcbc;
    background-position: top left;
    border-radius: 4px
}

.marvel-device.s5 .sensor {
    display: block;
    position: absolute;
    top: 20px;
    right: 110px;
    background: #3c3d3d;
    border-radius: 100%;
    width: 8px;
    height: 8px;
    z-index: 3
}

.marvel-device.s5 .sensor:after {
    display: block;
    content: '';
    position: absolute;
    top: 0px;
    right: 12px;
    background: #3c3d3d;
    border-radius: 100%;
    width: 8px;
    height: 8px;
    z-index: 3
}

.marvel-device.s5 .camera {
    display: block;
    position: absolute;
    top: 24px;
    right: 42px;
    background: black;
    border-radius: 100%;
    width: 10px;
    height: 10px;
    z-index: 3
}

.marvel-device.s5 .camera:before {
    width: 4px;
    height: 4px;
    background: #3c3d3d;
    border-radius: 100%;
    position: absolute;
    content: '';
    top: 50%;
    left: 50%;
    margin-top: -2px;
    margin-left: -2px
}

.marvel-device.s5 .home {
    position: absolute;
    z-index: 3;
    bottom: 17px;
    left: 50%;
    width: 70px;
    height: 20px;
    background: white;
    border-radius: 18px;
    display: block;
    margin-left: -35px;
    border: 2px solid black
}

.marvel-device.s5.landscape {
    padding: 18px 60px;
    height: 320px;
    width: 568px
}

.marvel-device.s5.landscape:before,
.marvel-device.s5.landscape:after {
    height: calc(100% - 52px);
    width: 26px;
    border-radius: 40px / 500px;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%)
}

.marvel-device.s5.landscape:before {
    top: 50%;
    left: -7px
}

.marvel-device.s5.landscape:after {
    top: 50%;
    left: auto;
    right: -7px
}

.marvel-device.s5.landscape .top-bar:before,
.marvel-device.s5.landscape .top-bar:after {
    width: 26px;
    height: calc(100% - 48px);
    border-radius: 40px / 500px;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%)
}

.marvel-device.s5.landscape .top-bar:before {
    right: -7px;
    top: 50%;
    left: auto
}

.marvel-device.s5.landscape .top-bar:after {
    left: -7px;
    top: 50%;
    right: auto
}

.marvel-device.s5.landscape .sleep {
    height: 3px;
    width: 100px;
    left: calc(100% - 200px);
    top: -3px;
    border-radius: 2px 2px 0px 0px
}

.marvel-device.s5.landscape .speaker {
    height: 68px;
    width: 8px;
    left: calc(100% - 20px);
    top: 50%;
    margin-left: 0;
    margin-top: -34px
}

.marvel-device.s5.landscape .sensor {
    right: 20px;
    top: calc(100% - 110px)
}

.marvel-device.s5.landscape .sensor:after {
    left: -12px;
    right: 0px
}

.marvel-device.s5.landscape .camera {
    top: calc(100% - 42px);
    right: 24px
}

.marvel-device.s5.landscape .home {
    width: 20px;
    height: 70px;
    bottom: 50%;
    margin-bottom: -35px;
    margin-left: 0;
    left: 17px
}

.marvel-device.s5.black {
    background: #1e1e1e
}

.marvel-device.s5.black .speaker {
    background: black
}

.marvel-device.s5.black .sleep {
    background: #1e1e1e
}

.marvel-device.s5.black .top-bar {
    background: radial-gradient(rgba(0, 0, 0, 0.05) 20%, transparent 60%) 0 0, radial-gradient(rgba(0, 0, 0, 0.05) 20%, transparent 60%) 3px 3px;
    background-color: #2c2b2c;
    background-size: 4px 4px
}

.marvel-device.s5.black .home {
    background: #2c2b2c
}

.marvel-device.lumia920 {
    padding: 80px 35px 125px 35px;
    background: #ffdd00;
    width: 320px;
    height: 533px;
    border-radius: 40px / 3px
}

.marvel-device.lumia920 .bottom-bar {
    display: none
}

.marvel-device.lumia920 .top-bar {
    width: calc(100% - 24px);
    height: calc(100% - 32px);
    position: absolute;
    top: 16px;
    left: 12px;
    border-radius: 24px;
    background: black;
    z-index: 1
}

.marvel-device.lumia920 .top-bar:before {
    background: #1e1e1e;
    display: block;
    content: '';
    width: calc(100% - 4px);
    height: calc(100% - 4px);
    top: 2px;
    left: 2px;
    position: absolute;
    border-radius: 22px
}

.marvel-device.lumia920 .volume {
    width: 3px;
    position: absolute;
    top: 130px;
    height: 100px;
    background: #1e1e1e;
    right: -3px;
    border-radius: 0px 2px 2px 0px
}

.marvel-device.lumia920 .volume:before {
    width: 3px;
    position: absolute;
    top: 190px;
    content: '';
    display: block;
    height: 50px;
    background: inherit;
    right: 0px;
    border-radius: 0px 2px 2px 0px
}

.marvel-device.lumia920 .volume:after {
    width: 3px;
    position: absolute;
    top: 460px;
    content: '';
    display: block;
    height: 50px;
    background: inherit;
    right: 0px;
    border-radius: 0px 2px 2px 0px
}

.marvel-device.lumia920 .camera {
    background: #3c3d3d;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 34px;
    right: 130px;
    z-index: 5;
    border-radius: 5px
}

.marvel-device.lumia920 .speaker {
    background: #292728;
    width: 64px;
    height: 10px;
    position: absolute;
    top: 38px;
    left: 50%;
    margin-left: -32px;
    border-radius: 5px;
    z-index: 3
}

.marvel-device.lumia920.landscape {
    padding: 35px 80px 35px 125px;
    height: 320px;
    width: 568px;
    border-radius: 2px / 100px
}

.marvel-device.lumia920.landscape .top-bar {
    height: calc(100% - 24px);
    width: calc(100% - 32px);
    left: 16px;
    top: 12px
}

.marvel-device.lumia920.landscape .volume {
    height: 3px;
    right: 130px;
    width: 100px;
    top: 100%;
    border-radius: 0px 0px 2px 2px
}

.marvel-device.lumia920.landscape .volume:before {
    height: 3px;
    right: 190px;
    top: 0px;
    width: 50px;
    border-radius: 0px 0px 2px 2px
}

.marvel-device.lumia920.landscape .volume:after {
    height: 3px;
    right: 430px;
    top: 0px;
    width: 50px;
    border-radius: 0px 0px 2px 2px
}

.marvel-device.lumia920.landscape .camera {
    right: 30px;
    top: calc(100% - 140px)
}

.marvel-device.lumia920.landscape .speaker {
    width: 10px;
    height: 64px;
    top: 50%;
    margin-left: 0;
    margin-top: -32px;
    left: calc(100% - 48px)
}

.marvel-device.lumia920.black {
    background: black
}

.marvel-device.lumia920.white {
    background: white;
    -webkit-box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2)
}

.marvel-device.lumia920.blue {
    background: #00acdd
}

.marvel-device.lumia920.red {
    background: #CC3E32
}

.marvel-device.htc-one {
    padding: 72px 25px 100px 25px;
    width: 320px;
    height: 568px;
    background: #bebebe;
    border-radius: 34px
}

.marvel-device.htc-one:before {
    content: '';
    display: block;
    width: calc(100% - 4px);
    height: calc(100% - 4px);
    position: absolute;
    top: 2px;
    left: 2px;
    background: #adadad;
    border-radius: 32px
}

.marvel-device.htc-one:after {
    content: '';
    display: block;
    width: calc(100% - 8px);
    height: calc(100% - 8px);
    position: absolute;
    top: 4px;
    left: 4px;
    background: #eeeeee;
    border-radius: 30px
}

.marvel-device.htc-one .top-bar {
    width: calc(100% - 4px);
    height: 635px;
    position: absolute;
    background: #424242;
    top: 50px;
    z-index: 1;
    left: 2px
}

.marvel-device.htc-one .top-bar:before {
    content: '';
    position: absolute;
    width: calc(100% - 4px);
    height: 100%;
    position: absolute;
    background: black;
    top: 0px;
    z-index: 1;
    left: 2px
}

.marvel-device.htc-one .bottom-bar {
    display: none
}

.marvel-device.htc-one .speaker {
    height: 16px;
    width: 216px;
    display: block;
    position: absolute;
    top: 22px;
    z-index: 2;
    left: 50%;
    margin-left: -108px;
    background: radial-gradient(#343434 25%, transparent 50%) 0 0, radial-gradient(#343434 25%, transparent 50%) 4px 4px;
    background-size: 4px 4px;
    background-position: top left
}

.marvel-device.htc-one .speaker:after {
    content: '';
    height: 16px;
    width: 216px;
    display: block;
    position: absolute;
    top: 676px;
    z-index: 2;
    left: 50%;
    margin-left: -108px;
    background: inherit
}

.marvel-device.htc-one .camera {
    display: block;
    position: absolute;
    top: 18px;
    right: 38px;
    background: #3c3d3d;
    border-radius: 100%;
    width: 24px;
    height: 24px;
    z-index: 3
}

.marvel-device.htc-one .camera:before {
    width: 8px;
    height: 8px;
    background: black;
    border-radius: 100%;
    position: absolute;
    content: '';
    top: 50%;
    left: 50%;
    margin-top: -4px;
    margin-left: -4px
}

.marvel-device.htc-one .sensor {
    display: block;
    position: absolute;
    top: 29px;
    left: 60px;
    background: #3c3d3d;
    border-radius: 100%;
    width: 8px;
    height: 8px;
    z-index: 3
}

.marvel-device.htc-one .sensor:after {
    display: block;
    content: '';
    position: absolute;
    top: 0px;
    right: 12px;
    background: #3c3d3d;
    border-radius: 100%;
    width: 8px;
    height: 8px;
    z-index: 3
}

.marvel-device.htc-one.landscape {
    padding: 25px 72px 25px 100px;
    height: 320px;
    width: 568px
}

.marvel-device.htc-one.landscape .top-bar {
    height: calc(100% - 4px);
    width: 635px;
    left: calc(100% - 685px);
    top: 2px
}

.marvel-device.htc-one.landscape .speaker {
    width: 16px;
    height: 216px;
    left: calc(100% - 38px);
    top: 50%;
    margin-left: 0px;
    margin-top: -108px
}

.marvel-device.htc-one.landscape .speaker:after {
    width: 16px;
    height: 216px;
    left: calc(100% - 692px);
    top: 50%;
    margin-left: 0;
    margin-top: -108px
}

.marvel-device.htc-one.landscape .camera {
    right: 18px;
    top: calc(100% - 38px)
}

.marvel-device.htc-one.landscape .sensor {
    left: calc(100% - 29px);
    top: 60px
}

.marvel-device.htc-one.landscape .sensor :after {
    right: 0;
    top: -12px
}

.marvel-device.ipad {
    width: 576px;
    height: 768px;
    padding: 90px 25px;
    background: #242324;
    border-radius: 44px
}

.marvel-device.ipad:before {
    width: calc(100% - 8px);
    height: calc(100% - 8px);
    position: absolute;
    content: '';
    display: block;
    top: 4px;
    left: 4px;
    border-radius: 40px;
    background: #1e1e1e
}

.marvel-device.ipad .camera {
    background: #3c3d3d;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 44px;
    left: 50%;
    margin-left: -5px;
    border-radius: 100%
}

.marvel-device.ipad .top-bar,
.marvel-device.ipad .bottom-bar {
    display: none
}

.marvel-device.ipad .home {
    background: #242324;
    border-radius: 36px;
    width: 50px;
    height: 50px;
    position: absolute;
    left: 50%;
    margin-left: -25px;
    bottom: 22px
}

.marvel-device.ipad .home:after {
    width: 15px;
    height: 15px;
    margin-top: -8px;
    margin-left: -8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    position: absolute;
    display: block;
    content: '';
    top: 50%;
    left: 50%
}

.marvel-device.ipad.landscape {
    height: 576px;
    width: 768px;
    padding: 25px 90px
}

.marvel-device.ipad.landscape .camera {
    left: calc(100% - 44px);
    top: 50%;
    margin-left: 0;
    margin-top: -5px
}

.marvel-device.ipad.landscape .home {
    top: 50%;
    left: 22px;
    margin-left: 0;
    margin-top: -25px
}

.marvel-device.ipad.silver {
    background: #bcbcbc
}

.marvel-device.ipad.silver:before {
    background: #fcfcfc
}

.marvel-device.ipad.silver .home {
    background: #fcfcfc;
    -webkit-box-shadow: inset 0 0 0 1px #bcbcbc;
    box-shadow: inset 0 0 0 1px #bcbcbc
}

.marvel-device.ipad.silver .home:after {
    border: 1px solid rgba(0, 0, 0, 0.2)
}

.marvel-device.macbook {
    width: 960px;
    height: 600px;
    padding: 44px 44px 76px;
    margin: 0 auto;
    background: #bebebe;
    border-radius: 34px
}

.marvel-device.macbook:before {
    width: calc(100% - 8px);
    height: calc(100% - 8px);
    position: absolute;
    content: '';
    display: block;
    top: 4px;
    left: 4px;
    border-radius: 30px;
    background: #1e1e1e
}

.marvel-device.macbook .top-bar {
    width: calc(100% + 2 * 70px);
    height: 40px;
    position: absolute;
    content: '';
    display: block;
    top: 680px;
    left: -70px;
    border-bottom-left-radius: 90px 18px;
    border-bottom-right-radius: 90px 18px;
    background: #bebebe;
    -webkit-box-shadow: inset 0px -4px 13px 3px rgba(34, 34, 34, 0.6);
    box-shadow: inset 0px -4px 13px 3px rgba(34, 34, 34, 0.6)
}

.marvel-device.macbook .top-bar:before {
    width: 100%;
    height: 24px;
    content: '';
    display: block;
    top: 0;
    left: 0;
    background: #f0f0f0;
    border-bottom: 2px solid #aaa;
    border-radius: 5px;
    position: relative
}

.marvel-device.macbook .top-bar:after {
    width: 16%;
    height: 14px;
    content: '';
    display: block;
    top: 0;
    background: #ddd;
    position: absolute;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    border-radius: 0 0 20px 20px;
    -webkit-box-shadow: inset 0px -3px 10px #999;
    box-shadow: inset 0px -3px 10px #999
}

.marvel-device.macbook .bottom-bar {
    background: transparent;
    width: calc(100% + 2 * 70px);
    height: 26px;
    position: absolute;
    content: '';
    display: block;
    top: 680px;
    left: -70px
}

.marvel-device.macbook .bottom-bar:before,
.marvel-device.macbook .bottom-bar:after {
    height: calc(100% - 2px);
    width: 80px;
    content: '';
    display: block;
    top: 0;
    position: absolute
}

.marvel-device.macbook .bottom-bar:before {
    left: 0;
    background: #f0f0f0;
    background: -webkit-gradient(linear, left top, right top, from(#747474), color-stop(5%, #c3c3c3), color-stop(14%, #ebebeb), color-stop(41%, #979797), color-stop(80%, #f0f0f0), color-stop(100%, #f0f0f0), to(#f0f0f0));
    background: linear-gradient(to right, #747474 0%, #c3c3c3 5%, #ebebeb 14%, #979797 41%, #f0f0f0 80%, #f0f0f0 100%, #f0f0f0 100%)
}

.marvel-device.macbook .bottom-bar:after {
    right: 0;
    background: #f0f0f0;
    background: -webkit-gradient(linear, left top, right top, from(#f0f0f0), color-stop(0%, #f0f0f0), color-stop(20%, #f0f0f0), color-stop(59%, #979797), color-stop(86%, #ebebeb), color-stop(95%, #c3c3c3), to(#747474));
    background: linear-gradient(to right, #f0f0f0 0%, #f0f0f0 0%, #f0f0f0 20%, #979797 59%, #ebebeb 86%, #c3c3c3 95%, #747474 100%)
}

.marvel-device.macbook .camera {
    background: #3c3d3d;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 20px;
    left: 50%;
    margin-left: -5px;
    border-radius: 100%
}

.marvel-device.macbook .home {
    display: none
}

.marvel-device.iphone-x {
    width: 375px;
    height: 812px;
    padding: 26px;
    background: #fdfdfd;
    -webkit-box-shadow: inset 0 0 11px 0 black;
    box-shadow: inset 0 0 11px 0 black;
    border-radius: 66px
}

.marvel-device.iphone-x .overflow {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 66px;
    overflow: hidden
}

.marvel-device.iphone-x .shadow {
    border-radius: 100%;
    width: 90px;
    height: 90px;
    position: absolute;
    background: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.6) 0%, rgba(255, 255, 255, 0) 60%)
}

.marvel-device.iphone-x .shadow--tl {
    top: -20px;
    left: -20px
}

.marvel-device.iphone-x .shadow--tr {
    top: -20px;
    right: -20px
}

.marvel-device.iphone-x .shadow--bl {
    bottom: -20px;
    left: -20px
}

.marvel-device.iphone-x .shadow--br {
    bottom: -20px;
    right: -20px
}

.marvel-device.iphone-x:before {
    width: calc(100% - 10px);
    height: calc(100% - 10px);
    position: absolute;
    top: 5px;
    content: '';
    left: 5px;
    border-radius: 61px;
    background: black;
    z-index: 1
}

.marvel-device.iphone-x .inner-shadow {
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    position: absolute;
    top: 10px;
    overflow: hidden;
    left: 10px;
    border-radius: 56px;
    -webkit-box-shadow: inset 0 0 15px 0 rgba(255, 255, 255, 0.66);
    box-shadow: inset 0 0 15px 0 rgba(255, 255, 255, 0.66);
    z-index: 1
}

.marvel-device.iphone-x .inner-shadow:before {
    -webkit-box-shadow: inset 0 0 20px 0 #FFFFFF;
    box-shadow: inset 0 0 20px 0 #FFFFFF;
    width: 100%;
    height: 116%;
    position: absolute;
    top: -8%;
    content: '';
    left: 0;
    border-radius: 200px / 112px;
    z-index: 2
}

.marvel-device.iphone-x .screen {
    border-radius: 40px;
    -webkit-box-shadow: none;
    box-shadow: none
}

.marvel-device.iphone-x .top-bar,
.marvel-device.iphone-x .bottom-bar {
    width: 100%;
    position: absolute;
    height: 8px;
    background: rgba(0, 0, 0, 0.1);
    left: 0
}

.marvel-device.iphone-x .top-bar {
    top: 80px
}

.marvel-device.iphone-x .bottom-bar {
    bottom: 80px
}

.marvel-device.iphone-x .volume,
.marvel-device.iphone-x .volume:before,
.marvel-device.iphone-x .volume:after,
.marvel-device.iphone-x .sleep {
    width: 3px;
    background: #b5b5b5;
    position: absolute
}

.marvel-device.iphone-x .volume {
    left: -3px;
    top: 116px;
    height: 32px
}

.marvel-device.iphone-x .volume:before {
    height: 62px;
    top: 62px;
    content: '';
    left: 0
}

.marvel-device.iphone-x .volume:after {
    height: 62px;
    top: 140px;
    content: '';
    left: 0
}

.marvel-device.iphone-x .sleep {
    height: 96px;
    top: 200px;
    right: -3px
}

.marvel-device.iphone-x .camera {
    width: 6px;
    height: 6px;
    top: 9px;
    border-radius: 100%;
    position: absolute;
    left: 154px;
    background: #0d4d71
}

.marvel-device.iphone-x .speaker {
    height: 6px;
    width: 60px;
    left: 50%;
    position: absolute;
    top: 9px;
    margin-left: -30px;
    background: #414444;
    border-radius: 6px
}

.marvel-device.iphone-x .notch {
    position: absolute;
    width: 210px;
    height: 30px;
    top: 26px;
    left: 108px;
    z-index: 4;
    background: black;
    border-bottom-left-radius: 24px;
    border-bottom-right-radius: 24px
}

.marvel-device.iphone-x .notch:before,
.marvel-device.iphone-x .notch:after {
    content: '';
    height: 8px;
    position: absolute;
    top: 0;
    width: 8px
}

.marvel-device.iphone-x .notch:after {
    background: radial-gradient(circle at bottom left, transparent 0, transparent 70%, black 70%, black 100%);
    left: -8px
}

.marvel-device.iphone-x .notch:before {
    background: radial-gradient(circle at bottom right, transparent 0, transparent 70%, black 70%, black 100%);
    right: -8px
}

.marvel-device.iphone-x.landscape {
    height: 375px;
    width: 812px
}

.marvel-device.iphone-x.landscape .top-bar,
.marvel-device.iphone-x.landscape .bottom-bar {
    width: 8px;
    height: 100%;
    top: 0
}

.marvel-device.iphone-x.landscape .top-bar {
    left: 80px
}

.marvel-device.iphone-x.landscape .bottom-bar {
    right: 80px;
    bottom: auto;
    left: auto
}

.marvel-device.iphone-x.landscape .volume,
.marvel-device.iphone-x.landscape .volume:before,
.marvel-device.iphone-x.landscape .volume:after,
.marvel-device.iphone-x.landscape .sleep {
    height: 3px
}

.marvel-device.iphone-x.landscape .inner-shadow:before {
    height: 100%;
    width: 116%;
    left: -8%;
    top: 0;
    border-radius: 112px / 200px
}

.marvel-device.iphone-x.landscape .volume {
    bottom: -3px;
    top: auto;
    left: 116px;
    width: 32px
}

.marvel-device.iphone-x.landscape .volume:before {
    width: 62px;
    left: 62px;
    top: 0
}

.marvel-device.iphone-x.landscape .volume:after {
    width: 62px;
    left: 140px;
    top: 0
}

.marvel-device.iphone-x.landscape .sleep {
    width: 96px;
    left: 200px;
    top: -3px;
    right: auto
}

.marvel-device.iphone-x.landscape .camera {
    left: 9px;
    bottom: 154px;
    top: auto
}

.marvel-device.iphone-x.landscape .speaker {
    width: 6px;
    height: 60px;
    left: 9px;
    top: 50%;
    margin-top: -30px;
    margin-left: 0
}

.marvel-device.iphone-x.landscape .notch {
    height: 210px;
    width: 30px;
    left: 26px;
    bottom: 108px;
    top: auto;
    border-top-right-radius: 24px;
    border-bottom-right-radius: 24px;
    border-bottom-left-radius: 0
}

.marvel-device.iphone-x.landscape .notch:before,
.marvel-device.iphone-x.landscape .notch:after {
    left: 0
}

.marvel-device.iphone-x.landscape .notch:after {
    background: radial-gradient(circle at bottom right, transparent 0, transparent 70%, black 70%, black 100%);
    bottom: -8px;
    top: auto
}

.marvel-device.iphone-x.landscape .notch:before {
    background: radial-gradient(circle at top right, transparent 0, transparent 70%, black 70%, black 100%);
    top: -8px
}

.marvel-device.note8 {
    width: 400px;
    height: 822px;
    background: black;
    border-radius: 34px;
    padding: 45px 10px
}

.marvel-device.note8 .overflow {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 34px;
    overflow: hidden
}

.marvel-device.note8 .speaker {
    height: 8px;
    width: 56px;
    left: 50%;
    position: absolute;
    top: 25px;
    margin-left: -28px;
    background: #171818;
    z-index: 1;
    border-radius: 8px
}

.marvel-device.note8 .camera {
    height: 18px;
    width: 18px;
    left: 86px;
    position: absolute;
    top: 18px;
    background: #212b36;
    z-index: 1;
    border-radius: 100%
}

.marvel-device.note8 .camera:before {
    content: '';
    height: 8px;
    width: 8px;
    left: -22px;
    position: absolute;
    top: 5px;
    background: #212b36;
    z-index: 1;
    border-radius: 100%
}

.marvel-device.note8 .sensors {
    height: 10px;
    width: 10px;
    left: 120px;
    position: absolute;
    top: 22px;
    background: #1d233b;
    z-index: 1;
    border-radius: 100%
}

.marvel-device.note8 .sensors:before {
    content: '';
    height: 10px;
    width: 10px;
    left: 18px;
    position: absolute;
    top: 0;
    background: #1d233b;
    z-index: 1;
    border-radius: 100%
}

.marvel-device.note8 .more-sensors {
    height: 16px;
    width: 16px;
    left: 285px;
    position: absolute;
    top: 18px;
    background: #33244a;
    -webkit-box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
    z-index: 1;
    border-radius: 100%
}

.marvel-device.note8 .more-sensors:before {
    content: '';
    height: 11px;
    width: 11px;
    left: 40px;
    position: absolute;
    top: 4px;
    background: #214a61;
    z-index: 1;
    border-radius: 100%
}

.marvel-device.note8 .sleep {
    width: 2px;
    height: 56px;
    background: black;
    position: absolute;
    top: 288px;
    right: -2px
}

.marvel-device.note8 .volume {
    width: 2px;
    height: 120px;
    background: black;
    position: absolute;
    top: 168px;
    left: -2px
}

.marvel-device.note8 .volume:before {
    content: '';
    top: 168px;
    width: 2px;
    position: absolute;
    left: 0;
    background: black;
    height: 56px
}

.marvel-device.note8 .inner {
    width: 100%;
    height: calc(100% - 8px);
    position: absolute;
    top: 2px;
    content: '';
    left: 0px;
    border-radius: 34px;
    border-top: 2px solid #9fa0a2;
    border-bottom: 2px solid #9fa0a2;
    background: black;
    z-index: 1;
    -webkit-box-shadow: inset 0 0 6px 0 rgba(255, 255, 255, 0.5);
    box-shadow: inset 0 0 6px 0 rgba(255, 255, 255, 0.5)
}

.marvel-device.note8 .shadow {
    -webkit-box-shadow: inset 0 0 60px 0 white, inset 0 0 30px 0 rgba(255, 255, 255, 0.5), 0 0 20px 0 white, 0 0 20px 0 rgba(255, 255, 255, 0.5);
    box-shadow: inset 0 0 60px 0 white, inset 0 0 30px 0 rgba(255, 255, 255, 0.5), 0 0 20px 0 white, 0 0 20px 0 rgba(255, 255, 255, 0.5);
    height: 101%;
    position: absolute;
    top: -0.5%;
    content: '';
    width: calc(100% - 20px);
    left: 10px;
    border-radius: 38px;
    z-index: 5;
    pointer-events: none
}

.marvel-device.note8 .screen {
    border-radius: 14px;
    -webkit-box-shadow: none;
    box-shadow: none
}

.marvel-device.note8.landscape {
    height: 400px;
    width: 822px;
    padding: 10px 45px
}

.marvel-device.note8.landscape .speaker {
    height: 56px;
    width: 8px;
    top: 50%;
    margin-top: -28px;
    margin-left: 0;
    right: 25px;
    left: auto
}

.marvel-device.note8.landscape .camera {
    top: 86px;
    right: 18px;
    left: auto
}

.marvel-device.note8.landscape .camera:before {
    top: -22px;
    left: 5px
}

.marvel-device.note8.landscape .sensors {
    top: 120px;
    right: 22px;
    left: auto
}

.marvel-device.note8.landscape .sensors:before {
    top: 18px;
    left: 0
}

.marvel-device.note8.landscape .more-sensors {
    top: 285px;
    right: 18px;
    left: auto
}

.marvel-device.note8.landscape .more-sensors:before {
    top: 40px;
    left: 4px
}

.marvel-device.note8.landscape .sleep {
    bottom: -2px;
    top: auto;
    right: 288px;
    width: 56px;
    height: 2px
}

.marvel-device.note8.landscape .volume {
    width: 120px;
    height: 2px;
    top: -2px;
    right: 168px;
    left: auto
}

.marvel-device.note8.landscape .volume:before {
    right: 168px;
    left: auto;
    top: 0;
    width: 56px;
    height: 2px
}

.marvel-device.note8.landscape .inner {
    height: 100%;
    width: calc(100% - 8px);
    left: 2px;
    top: 0;
    border-top: 0;
    border-bottom: 0;
    border-left: 2px solid #9fa0a2;
    border-right: 2px solid #9fa0a2
}

.marvel-device.note8.landscape .shadow {
    width: 101%;
    height: calc(100% - 20px);
    left: -0.5%;
    top: 10px
}
.mytoppadding {
    padding-top: 25px;
    padding-right: 1150px;
}

div.text-success.new_text {

    color: #f00;
    font-size: 35px;
    border-spacing: 63px;
    vertical-align: middle;
    margin-top: 30%;
    text-align: center;
}

.button-now.new_text {
    margin-top: 30%;
}

.copy-notification {
            color: #ffffff;
            background-color: rgba(0,0,0,0.8);
            padding: 20px;
            border-radius: 30px;
            position: fixed;
            top: 50%;
            left: 50%;
            width: 150px;
            margin-top: -30px;
            margin-left: -85px;
            display: none;
            text-align:center;
        }
</style>
<div class="content" style="overflow: hidden;">
    <div class="row" style="margin-left:850px!important;">
        <div class="col-md-12">
            <div class="center mytoppadding">
                <div class="marvel-device iphone-x">
                    <div class="notch">
                        <div class="camera"></div>
                        <div class="speaker"></div>
                    </div>
                    <div class="top-bar"></div>
                    <div class="sleep"></div>
                    <div class="volume"></div>
                    <!-- <div class="camera"></div> -->
                    <div class="sensor"></div>
                    <!-- <div class="speaker"></div> -->
                    <div class="overflow">
                        <div class="shadow shadow--tr"></div>
                        <div class="shadow shadow--tl"></div>
                        <div class="shadow shadow--br"></div>
                        <div class="shadow shadow--bl"></div>
                    </div>
                    <div class="inner-shadow"></div>
                    <div class="screen">
                        <div class="text-center">
                            <div class="text-success new_text">Super Password</div>
                            <div class="text-success  new_text" id="password"><?=$password?></div>
                            <div class="button-now new_text"> <a href="javascript:void(0)" class="btn btn-success btn-md test" style="border-radius: 10px;margin-top: 9px;">Copy to Clipboard</a></div>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $(".test").click(function (event) {
                                        event.preventDefault();
                                        CopyToClipboard($("#password").html(), true, "Copied to Clipboard Successfully");
                                    });
                                });
                                function CopyToClipboard(value, showNotification, notificationText) {
                                    var $temp = $("<input>");
                                    $("body").append($temp);
                                    $temp.val(value).select();
                                    document.execCommand("copy");
                                    $temp.remove();

                                    if (typeof showNotification === 'undefined') {
                                        showNotification = true;
                                    }
                                    if (typeof notificationText === 'undefined') {
                                        notificationText = "Copied to clipboard";
                                    }

                                    var notificationTag = $("div.copy-notification");
                                    if (showNotification && notificationTag.length == 0) {
                                        notificationTag = $("<div/>", { "class": "copy-notification", text: notificationText });
                                        $("body").append(notificationTag);

                                        notificationTag.fadeIn("slow", function () {
                                            setTimeout(function () {
                                                notificationTag.fadeOut("slow", function () {
                                                    notificationTag.remove();
                                                });
                                            }, 1000);
                                        });
                                    }
                                }       
                            </script>
                        </div>
                    </div>
                    <div class="home"></div>
                    <div class="bottom-bar"></div>
                </div>
            </div>
        </div>
    </div>
</div>