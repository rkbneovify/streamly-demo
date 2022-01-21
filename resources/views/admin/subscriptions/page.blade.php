<?php
$variable=[1,2,3,4,5,6,7,8,9,10,1,2,3,4,5,6,7,8,9,10,111,2,3,4,5,6,7,8,9,10,1,2,3,4,5,6,7,8,9,10,11];
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Infinite Scroll + Pagination</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

</head>
<style>

body {
  background-color: #f8f8f8;
  color: #333;
  counter-reset: page;
  -webkit-font-smoothing: antialiased;
  -moz-font-smoothing: grayscale;
}

h1, h2, h3, h4, h5, h6 {
  text-transform: uppercase;
}

a {
  color: #28f;
  text-decoration: none;
}
a:hover, a:focus {
  color: #0062d5;
}
a:active {
  color: #003f88;
}

main {
  margin: 0 auto;
  max-width: 30em;
  padding: 1em;
}

.article-list__page {
  border-top: 1px solid #ddd;
  clear: both;
  counter-increment: page;
  padding-bottom: 3em;
  position: relative;
}
.article-list__page:before {
  background-color: #ddd;
  display: inline-block;
  content: counter(page);
  color: #888;
  padding: 0.25em 0.5em;
  position: absolute;
  left: calc(50% - .75em);
  top: -0.75em;
  vertical-align: middle;
  z-index: 1;
}

.article-list__item {
  background-color: #eee;
  float: left;
  height: 15em;
  max-width: 50%;
  opacity: 0.75;
  transform: scale(0.8);
  transition: opacity 0.2s, transform 0.2s;
  width: 15em;
}
.article-list__item:hover {
  opacity: 1;
  transform: scale(1);
}

.article-list__item__image {
  display: block;
  height: auto;
  margin: 0;
  opacity: 1;
  transition: opacity 0.2s;
  width: 100%;
}

.article-list__item__image--loading {
  opacity: 0;
}

.article-list__pagination {
  background-color: #222;
  box-shadow: 0 0 1em rgba(0, 0, 0, 0.25);
  display: block;
  bottom: 0;
  left: 0;
  list-style-type: none;
  margin: 0;
  padding: 0.5em;
  position: fixed;
  right: 0;
  text-align: center;
  transform: translateY(0);
  transition: transform 0.2s;
  z-index: 2;
}

.article-list__pagination--inactive {
  transform: translateY(100%);
}

.article-list__pagination__item {
  display: inline-block;
  margin: 0 1em;
}
.article-list__pagination__item a {
  color: #888;
  text-decoration: none;
}
.article-list__pagination__item a:hover, .article-list__pagination__item a:focus {
  color: #aeaeae;
}
.article-list__pagination__item a:active {
  color: #d5d5d5;
}

.ajax-loader {
  visibility: hidden;
  background-color: rgba(255,255,255,0.7);
  position: absolute;
  z-index: +100 !important;
  width: 100%;
  height:100%;
}

.ajax-loader img {
  position: relative;
  top:50%;
  left:50%;
}</style>
<body>

<div class="ajax-loader">
  <img src="{{url('../frontend/img/user.png')}}"  class="img-responsive" />
</div>
<main>
	<h1>Infinite Scroll + Pagination Experiment</h1>
	
	<table id="mytable" ><thead>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Job Title</th>
          <th>Favorite</th>
        </tr>
    </thead>
    <tbody class="article-list" id="article-list">
    <tr></tr>
    </tbody>
    </table>
	<ul class="article-list__pagination article-list__pagination--inactive" id="article-list-pagination"></ul>
</main>

</body>
</html>
<script>

function getPageId(n) {
  return 'article-page-' + n;
}

function getDocumentHeight() {
  const body = document.body;
  const html = document.documentElement;

  return Math.max(
  body.scrollHeight, body.offsetHeight,
  html.clientHeight, html.scrollHeight, html.offsetHeight);

};

function getScrollTop() {
  return window.pageYOffset !== undefined ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
}
// create image element
//<tr><td>96</td></tr>
		
function getArticleImage() {
  const hash = Math.floor(Math.random() * Number.MAX_SAFE_INTEGER);
  const image = document.createElement('td');
  image.className = 'article-list__item__image article-list__item__image--loading';
 
  image.appendChild(document.createTextNode(hash))
image.classList.remove('article-list__item__image--loading');
  /**image.onload = function () {
   image.classList.remove('article-list__item__image--loading');
  }; */

  return image;
}
// create image and append it article div
function getArticle(arg,i) {
  

  var r = document.createElement("TR");
                r.innerHTML = `
                                             <tr >
                                                <td >${arg.id}</td>
                                                <td>${arg.customer.email}</td>
                                                <td>${i}</td>
                                                <td>${page}</td>
                                            </tr>`;
                                            return r;
}
// article per page 16,big div that contains 16 images
function getArticlePage(page, articlesPerPage = 30) {
  

  //const pageElement = document.createElement('div');
 // pageElement.id = getPageId(page);
  //pageElement.className = 'article-list__page';
  var avc = document.createElement("TR");
  //console.log("getArticlePage",page);
  avc.id = getPageId(page);
avc.innerHTML=`<tr>
    <td colspan="4" style="text-align:center;color:#fafafa; background-color: #222;" >${page}</td>
  </tr>`;
  $('#mytable tr:last').after(avc);
  console.log("fulldata.length",fulldata.length);
  for (let i = 0; i < fulldata.length; i++) {
  
    $('#mytable tr:last').after(getArticle(fulldata[i],i));
}
  

  //return avc;
}
// get page number from url add pagination number in the bottom
function addPaginationPage(page) {
  const pageLink = document.createElement('a');
  pageLink.href = '#' + getPageId(page);
  pageLink.innerHTML = page;

  const listItem = document.createElement('li');
  listItem.className = 'article-list__pagination__item';
  listItem.appendChild(pageLink);

  articleListPagination.appendChild(listItem);

  if (page === 2) {
    articleListPagination.classList.remove('article-list__pagination--inactive');
  }
}
//fetch page and append 16 page
function fetchPage(page) {
 
if(nextid&&page!==1){callAPI(nextid);}
  
  



}
var nextid;
var has_more;
// addpage ,fetch page and add page number
function addPage(page) {
  if(has_more||page==1){
  fetchPage(page);
  addPaginationPage(page);}else{
  console.log("finished scroll");
}
}

const articleList = document.getElementById('article-list');
const articleListPagination = document.getElementById('article-list-pagination');
let page = 0;
callAPI();


window.onscroll = function () {
  if (getScrollTop() < getDocumentHeight() - window.innerHeight) return;
  addPage(++page);
};

function callAPI(param){
  console.log("param",param);
$.ajax({
							 
    url:"{{ url('admin/subscriptionsstripe') }}",

    type:"GET",
    data:{lastid: param,
    //customer:'cus_JgeZ3afo13HWyx'
    },
    dataType: 'json',beforeSend : function(){
     $('.ajax-loader').css("visibility", "visible");
  },
    success: function (data) {
      console.log(data);
     nextid=data.lastid.id;
     fulldata=data.fulldata;
     has_more=data.has_more;
     console.log("page",page);
     if(page==0){getArticlePage(1);}else{getArticlePage(page);}
     
    addPage(++page);
   

    },
    error:function(xhr, status, error){ 
     console.log("error",xhr);
       // coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
    }
}).done(function() {
  //  alert( "success" );

    $('.ajax-loader').css("visibility", "hidden");
  });}
</script>
