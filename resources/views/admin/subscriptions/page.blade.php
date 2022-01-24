<!DOCTYPE html>
<html>

<head>
	<title>Infinite scroll</title>
	<meta charset="UTF-8" />
	
	
</head>

<body>
<table id="mytable" ><thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Gender</th>
          <th>Country</th>
        </tr>
    </thead>
    <tbody class="whole_wrapper" id="article-list">
    <tr></tr>
    </tbody>
    </table>
	
	<ul class="article-list__pagination article-list__pagination--inactive" id="article-list-pagination"></ul>
	
</body>

</html>
<style>
.whole_wrapper {
  background: rgba(0, 0, 0, .1);
  width: 100%;
  min-height: 100%;
  padding:5%;
}
.whole_wrapper .each_card {
  width: 50%;
  align-items: center;
  text-align: center;
  display: flex;
  padding: 10px;
  background: white;
  margin:5% 25%;
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
}
.whole_wrapper .each_card .image_container {
  text-align: left;
}
.whole_wrapper .each_card .image_container img {
  width: 50%;
  border-radius: 10px;
}
.whole_wrapper .each_card .right_contents_container {
  display: flex;
  flex-direction: column;
}
.whole_wrapper .each_card .right_contents_container .name_field {
  font-size: 22px;
  font-weight: 900;
  line-height: 30px;
}
.whole_wrapper .each_card .right_contents_container .email_field {
  font-size: 22px;
  line-height: 30px;
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
</style>
<script>
let page = 1;
const last_page = 10;
var gotlastid=null;
var has_more=true;
var list=null;
const articleListPagination = document.getElementById('article-list-pagination');
const pixel_offset = 200;
const throttle = (callBack, delay) => {
  let withinInterval;
  return function () {
    const args = arguments;
    const context = this;
    if (!withinInterval) {
      callBack.call(context, args);
      withinInterval = true;
      setTimeout(() => withinInterval = false, delay);
    }
  };
};

const httpRequestWrapper = (method, URL) => {
  if(page!==1){list = gotlastid??null;
   console.log("lastchild",list);
  }
   const params = {
            lastid: list
        }
  return new Promise((resolve, reject) => {
    const xhr_obj = new XMLHttpRequest();
    xhr_obj.responseType = "json";
    xhr_obj.open(method, URL+`?lastid=${gotlastid??''}`);
    xhr_obj.setRequestHeader('Content-type', 'application/json')
    xhr_obj.onload = () => {
      const data = xhr_obj.response;
      resolve(data);
    };
    xhr_obj.onerror = () => {
      reject("failed");
    };
    xhr_obj.send();
  });
};

const getData = async (page_no = 1) => {
  const data = await httpRequestWrapper(
  "GET",
  "{{ url('admin/subscriptionsstripe') }}");


  const { fulldata,lastid, } = data;
  const { id } = lastid;
  gotlastid=id;
  has_more=data.has_more;
  console.log("data",data);
  console.log("has_more",has_more);
  populateUI(fulldata);
};

let handleLoad;

let trottleHandler = () => {throttle(handleLoad.call(this), 1000);};

document.addEventListener("DOMContentLoaded", () => {
  getData(1);
  window.addEventListener("scroll", trottleHandler);
});

handleLoad = () => {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - pixel_offset) {
    page = page + 1;
    if (page <= last_page) {
      window.removeEventListener('scroll', trottleHandler);
      console.log("page",page);
      if(has_more){getData(page).
      then(res => {
        window.addEventListener('scroll', trottleHandler);
      });}else{
        console.log("finished scroll");
      }
      
    }
  }
};






const populateUI = data => {
  const container = document.querySelector('.whole_wrapper');
  const pageElement = document.createElement('tr');
  
  console.log("datapopulateUI",data);
  var trtop;
  trtop=`<tr >
    <td id="${getPageId(page)}" colspan="4" style="text-align:center;color:#fafafa; background-color: #222;" >${page}</td>
  </tr>`;

var newRow = container.insertRow(container.rows.length);
newRow.innerHTML = trtop;
 
  pageElement.id = getPageId(page);
  pageElement.className = 'article-list__page';
	addPaginationPage(page);
  buildTable(data);

};

function getPageId(n) {
	return 'article-page-' + n;
}


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

function buildTable(contacts) {
  

  // Add the table content.
  // addLineToHTMLTable(element.fname, element.lname)
  contacts.forEach(function(curr) {
    addLineToHTMLTable(curr.id, curr.customer.email, curr.status, curr.start_date);
  });

}

// Add a line to the HTML table
function addLineToHTMLTable(firstName, lastName,gender,country) {
  // Get the body of the table using the selector API
  var tableBody = document.querySelector("#article-list");

  // Add a new row at the end of the table
  var newRow = tableBody.insertRow();

  // add  new cells to the row
  var firstNameCell = newRow.insertCell();
  firstNameCell.innerHTML = firstName;

  var lastNameCell = newRow.insertCell();
  lastNameCell.innerHTML = lastName;

  var genderCell = newRow.insertCell();
  genderCell.innerHTML = gender;

  var locationCell = newRow.insertCell();
  locationCell.innerHTML = country;
}
</script>