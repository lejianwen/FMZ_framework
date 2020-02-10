<template>
  <el-form-item ref="areaList" :label="label" :prop="prop">
    <el-select v-model="currentProvince" clearable placeholder="省" @change="changeProvince">
      <el-option v-for="(cities, name) in pca" :key="name" :label="name" :value="name" />
    </el-select>
    <el-select v-model="currentCity" clearable placeholder="市" @change="changeCity">
      <el-option v-for="(counties, name) in cities" :key="name" :label="name" :value="name" />
    </el-select>
    <el-select v-model="currentCounty" clearable placeholder="区" @change="changeCounty">
      <el-option v-for="item in counties" :key="item" :label="item" :value="item" />
    </el-select>
  </el-form-item>
</template>

<script>
export default {
  name: 'AreaList',
  props: {
    prop: {
      type: String,
      default: ''
    },
    label: {
      type: String,
      default: '省/市/区'
    },
    province: {
      type: String,
      default: ''
    },
    city: {
      type: String,
      default: ''
    },
    county: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      pca: require('@/utils/pca.json')
    }
  },
  computed: {
    cities() {
      return this.pca[this.province] || []
    },
    counties() {
      return this.pca[this.province] && this.pca[this.province][this.city] ? this.pca[this.province][this.city] : []
    },
    currentProvince: {
      get() {
        return this.province
      },
      set(val) {
        this.$emit('update:province', val)
      }
    },
    currentCity: {
      get() {
        return this.city
      },
      set(val) {
        this.$emit('update:city', val)
      }
    },
    currentCounty: {
      get() {
        return this.county
      },
      set(val) {
        this.$emit('update:county', val)
      }
    }
  },
  created() {
  },
  methods: {
    changeProvince(val) {
      this.currentCity = ''
      this.currentCounty = ''
      this.$emit('changeProvince', val)
    },
    changeCity(val) {
      this.currentCounty = ''
      this.$emit('changeCity', val)
    },
    changeCounty(val) {
      this.$emit('changeCounty')
    }
  }
}
</script>

<style scoped>

</style>
