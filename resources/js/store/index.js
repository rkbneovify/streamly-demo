export default {

	state: {

       category: []

	},

	getters: {

       getCategoryFormGetters(state){ //take parameter state

          return state.category
       }
	},

	actions: {
       allCategoryFromDatabase(context){

          axios.get("https://jsonplaceholder.typicode.com/posts")

               .then((response)=>{
                  console.log(response.data)
                  context.commit("categories",response.data) //categories will be run from mutation

               })

               .catch(()=>{
                  
                  console.log("Error........")
                  
               })
       }
	},

	mutations: {
       categories(state,data) {
          return state.category = data
       }
	}
}