<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12" style="padding:0px;">
                <select v-model="selectDependencia" name="dependencia_id" class="select list-group" required="true" size="7" style="width: 100%;">

                  <option v-for="option in direcciones" :value="option.id" :disabled="!option.has_turns" >
                    {{ option.nombre }}
                  </option>
                </select>
            </div>
        </div>
        <p style="color: #000; padding: 0px; margin: 0px;">TRÁMITES</p>
        <div class="row">
            <div class="col-lg-12 col-md-12" style="padding:0px;">
                <select v-model="selectTramite" name="dependencia_tramite_id" class="select list-group" required="true" size="7" style="width: 100%;">
                  <option v-for="option in tramites" :value="option.id" v-bind:disabled="!option.has_turns">
                    {{ option.nombre }}
                  </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectDependencia: this.dep_select_id == 0 ? '' : this.dep_select_id,
            selectTramite: '',
            direcciones: this.getDirecciones(), 
            tramites: []
        }
    },
    created: function () {
        tramites: this.getTramites()
    },
    props: {
        dep_select_id: String
    },
    watch: {
        selectDependencia: function() {
            // Clear previously selected values
            this.tramites = [];
            this.selectTramite = '';
            
            // Populate list of countries in the second dropdown
            if (this.selectDependencia != '') {
                this.getTramites();
            }
        }
    },
    methods: {
      getDirecciones: function (event) {
        axios 
        .get('getdirecciones')
        .then(
          response => {
            this.direcciones = response.data
          }
        )
      },
      getTramites: function (event) {
        this.selectDependencia != 0 && (
        axios
        .get('gettramites/'+this.selectDependencia)
        .then(
          response => {
            this.tramites = response.data
          }
        ))
      }
    }
}
</script>
