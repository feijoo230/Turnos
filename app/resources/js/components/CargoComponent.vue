<template>
  <div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Dependencia:</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="dependencia_id" id="dependencia_id" class="form-control" v-model="selectedDependencia">
          <option value="">SELECCIONAR...</option>
          <option 
            v-for="dep in dependencias" 
            :value="dep.key"
          >{{ dep.value }}
          </option>
        </select>
      </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Depende de:</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select name="parent_id" id="parent_id" class="form-control" :disabled="cargos.length == 0">
              <option value="">SELECCIONAR...</option>
              <option 
                v-for="(val, index) in cargos" 
                :value="index"
                :selected="selectedCargos == index ? true : false"
              >{{ val }}
              </option>
          </select>
        </div>
    </div>
  </div>
</template>
<script>
export default {

  created() {
    
  },
  props: ['dep_id', 'par_id'],
  data() {
    return {
      dependencias: this.getDependencias(),
      cargos: this.initCargos(this.dep_id),
      selectedDependencia: this.dep_id,
      selectedCargos: this.par_id
    }
  },
  watch: {
    selectedDependencia: function() {
      // Clear previously selected values
      this.cargos = [];
      this.selectedCargos = "";
      // Populate list of countries in the second dropdown      
      this.getCargos(this.selectedDependencia)      
    }
  },
  methods: {
    getDependencias: function (event) {
      axios
      .get(route('dependencias12'))
      .then(
        response => {
          this.dependencias = response.data.asociativo
        }
      )
    },
    initCargos: function (param) {
      if (param == null){
        return []
      } else {
        var _token = $("input[name='_token']").val();
        axios
        .post(route('dependenciacargo'), {_token:_token, dependencia_id: param})
        .then(
          response => {
            this.cargos = response.data
          }
        )
      }
      
    },
    getCargos: function (param) {
      var _token = $("input[name='_token']").val();
      axios
      .post(route('dependenciacargo'), {_token:_token, dependencia_id: param})
      .then(
        response => {
          this.cargos = response.data
        }
      )
    }
  }
}
</script>