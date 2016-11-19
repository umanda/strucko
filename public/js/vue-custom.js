
new Vue({
    el: '#app',
    data: {
        title: 'Strucko',
        subtitle: 'IT Dictionary',
        home_header_1: ''
    },
    methods: {
        changeHomeHeader1: function (event) {
            this.home_header_1 = event.target.value;
        }
    }
});