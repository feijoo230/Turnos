<template>
  <a @click="toggle"><i :class="classes" style="font-size: 20px;"></i></a>
</template>

<script>
    export default {
      props: ['id', 'isfavorite'],
      mounted() {
        console.log('Component mounted.')
      },
      
      computed: {
        classes() {
            return ['fa', this.isfavorite ? 'fa-star' : 'fa-star-o'];
        }
      },
      methods: {
        toggle: _.debounce(
            function () {
                this.isfavorite ? this.destroy() : this.create();
            },
            0
        ),
        create() {          
          axios.post('resolucion/favorito/', {resolucion_id: this.id})
            .then(response => {
                this.isfavorite = !this.isfavorite;
            });

        },
        destroy() {          
          axios.post('resolucion/unfavorito/', {'resolucion_id': this.id})
            .then(response => {
                this.isfavorite = !this.isfavorite;                
            })
            .catch(error => {

            });
        },
      }
    }
</script>

<style scoped>
    .fa {
        color: #f8cb00;
    }

    a:hover {
        cursor: pointer;
    }
</style>
