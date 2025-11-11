<template>
  <div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipos de Cargo:</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input v-model="cantidad" type="text" @input="addEvent" @change="addEvent" />
      </div>
    </div>
    <div class="form-group" v-show.visible="cantidad == 1">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Ingrese el Codigo del Cargo (opcional):</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" @input="addEvent" @change="addEvent" />
      </div>
    </div>
  </div>
</template>
<script>
export default {

  created() {
    
  },

  data() {
      return {
        cantidad: ''
      }
  },
  watch: {
    
  },
  
  methods: {
    addEvent : function (event){
 
    }
  }
}
</script>