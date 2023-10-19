import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import TVGuide from './EPG/components/TVGuide';


if (document.getElementById('dutylist')) {
    ReactDOM.render(<TVGuide />, document.getElementById('dutylist'));
}
