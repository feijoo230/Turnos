 <template>
  <div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dependencia:</label>
        <div class="col-md-6 col-sm-6 col-xs-12">          
          <v-select placeholder="SELECCIONAR" :class="form-control" :value="selected" :options="options" :on-change="getCargos"></v-select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Depende de:</label>
        <div class="col-md-6 col-sm-6 col-xs-12">          
          <v-select placeholder="SELECCIONAR" :class="form-control" :options="optionscargos"></v-select>
        </div>
    </div>
  </div>
</template>
<script>
  import vSelect from 'vue-select';

  export default {
    components: {
      'v-select': vSelect
    },
    data() {
      return {
        options: this.getDependencias(),
        optionscargos: [],
        selected: {id: 3, label: 'RECTORADO'},      
      }
    },
    methods: {
      getDependencias: function (event) {
        axios
        .get('./../dependencias13')
        .then(
          response => {
            this.options = response.data.asociativo
          }
        )
      },
      getCargos: function (param) {
        axios
        .post('./../dependenciacargo1', {'dependencia_id': param.id})
        .then(          
          response => {
            this.optionscargos = response.data.asociativo
          }
        )
      }
    }
  }
</script>