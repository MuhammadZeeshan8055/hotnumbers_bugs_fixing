/*
Copyright 2017 Ziadin Givan

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

https://github.com/givanz/Vvvebjs
*/

Vvveb.SectionsGroup['Sections'] =
["site/top-banner"];

Vvveb.Sections.add("site/top-banner", {
    name: "Top Banner",
	dragHtml: '<img src="'+site_url+'/assets/admin/builder/images/top-banner-preview.png">',
    image: site_url+'/assets/admin/builder/images/top-banner-preview.png',
    html: `
    <section class=" heading_box no_margins no_padding high_box home_banner">
    <img src="${site_url+'/assets/admin/builder/images/top-banner-image.jpg'}">
    <div class="container">
        <div class="heading top-page">
            <h1>Hot Numbers <br>Coffee Roasters</h1>                 
        </div>

      <div class="has_btn_bottom has_btn2_bottom" style="text-align: center;">
        <a class="button button_lrg  button_bottom" href="http://localhost:8080/Dropbox/xamp/hotnumbers/shop"> Shop Now </a>
        <a class="button button_lrg  button_bottom" href="http://localhost:8080/Dropbox/xamp/hotnumbers/workwithus"> Work with us! </a>
      </div>
    </div>
    <style>
    html body .wrapper .high_box {
        height: 90vh;
        min-height: 800px;
    }
    html body .wrapper .heading_box .bg {
        display: block;
        position: absolute;
        z-index: 0;
        width: 100%;
        height: 100%;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        background-position: 50% 50% !important;
        opacity: 0;
        -webkit-animation: zoomin 5s linear 500ms forwards;
        animation: zoomin 5s linear 500ms forwards;
    }
    .heading_box .overlay {
    display: block;
    position: absolute;
    z-index: 1;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    opacity: 0;
    mix-blend-mode: darken;
    box-shadow: inset 0px 0px 200px rgba(0, 0, 0, 0.8);
    }
    .heading_box .heading {
    display: block;
    width: 100%;
    top: 50%;
    text-align: center;
    line-height: 1.2em;
    position: relative;
    z-index: 2;
    font-weight: 500;
    color: #fff;
    letter-spacing: 0px;
    margin: 0px auto;
    padding: 0em 2em 0em;
    max-width: 965px;
    }
    </style>
</section>
`,
});

