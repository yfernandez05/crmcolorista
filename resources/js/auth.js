let loggedInUser = document.head.querySelector('meta[name="user"]');

module.exports = {
    computed: {
        authenticatedUser(){
            return JSON.parse(loggedInUser.content);
        },
        isAuthenticated(){
            return !! loggedInUser.content;
        }
    }
}