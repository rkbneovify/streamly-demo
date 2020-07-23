// To create the singleton behavior
let instance = null;

export default class Singleton {

  constructor() {
     if (instance) return instance;

     instance = this;

     return instance;
  }

  // To get the singleton object
  getInstance() {
     return instance || new Singleton();
  }

  storeVar(v) {
    this.v = v;
  }

  getVar() {
    return this.v;
  }
}