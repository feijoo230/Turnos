<template>
  <div>
    <select name="legajo_personal" class="form-control col-md-7 col-xs-12">
      <option value="">SELECCIONAR...</option>
      <option v-for="legajo in legajos" :value="legajo.key">
        {{ legajo.value }}
      </option>
    </select>    
  </div>
</template>

<script>
  import vSelect from 'vue-select';

  export default {
    components: {
      'v-select': vSelect
    },
    props: ['legajos'],
    data: {
      legajo_personal: ''
    }
  }
</script>