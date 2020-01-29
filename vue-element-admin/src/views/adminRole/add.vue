<template>
  <div class="app-container admin-role">
    <div class="form-container">
      <base-form
        ref="baseForm"
        :ref-id="ref"
        :form-options="formOptions"
        :rules="rules"
        :value="item"
        :disabled="formDisabled"
        @handleCancel="onCancel"
        @handleSubmit="onSubmit"
        @handleSwitchChange="handleSwitchChange"
      >
        <template slot="route_names">
          <el-form-item
            label="路由选择"
            prop="route_names"
          >
            <el-tree
              ref="tree"
              :data="routesOptions"
              :props="{children:'children',label:'label'}"
              node-key="value"
              :default-expanded-keys="item.route_names"
              :default-checked-keys="item.route_names"
              show-checkbox
            />
          </el-form-item>
        </template>

      </base-form>
    </div>

  </div>
</template>

<script>
const model = 'adminRole'
import BaseForm from '@/components/basePage/baseForm'
import { createItem, updateItem, itemDetail } from '@/api/adminRole'
import { asyncRoutes } from '@/router'
export default {
  name: `${model}Add`,
  components: { BaseForm },
  data() {
    return {
      time: '',
      formDisabled: false,
      rules: {
        name: [{ required: true, message: '名称必须的' }]
      },
      formOptions: [
        { type: 'input', prop: 'name', label: '名称' },
        { type: 'switch', prop: 'change_routes', label: '路由选择', activeText: '所有', inactiveText: '选择' }
      ],
      ref: 'baseFormChild',
      item: {
        name: '',
        route_names: [],
        change_routes: 1
      },
      routesOptions: []
    }
  },
  computed: {
    isEdit() {
      return this.$route.name === `${model}Edit`
    },
    isDetail() {
      return this.$route.name === `${model}Detail`
    }
  },
  created() {
    this.routesOptions = this.formatterRoutes(asyncRoutes)
    if (this.isEdit || this.isDetail) {
      itemDetail(this.$route.params.id).then(res => {
        this.item = res.data
        if (this.item.route_names === '*') {
          this.item.change_routes = 1
        } else {
          this.item.route_names = JSON.parse(this.item.route_names)
          this.formOptions.push({ type: 'slot', prop: 'route_names', label: '允许路由' })
        }
      })
    }
    if (this.isDetail) {
      this.formDisabled = true
    }
  },
  methods: {
    handleSwitchChange({ formItem, $event }) {
      if ($event === 0) {
        if (this.formOptions.length === 2) {
          if (this.item.route_names === '*') {
            this.item.route_names = []
          }
          this.formOptions.push({ type: 'slot', prop: 'route_names', label: '允许路由' })
        }
      } else if ($event === 1) {
        if (this.formOptions.length === 3) { this.formOptions.splice(2, 1) }
      }
    },
    formatterRoutes(routes) {
      const res = []

      routes.forEach(route => {
        const tmp = { label: ((route.meta && route.meta.title) ? route.meta.title : ''), value: route.path }
        if (route.children) {
          tmp.children = this.formatterRoutes(route.children)
        }
        res.push(tmp)
      })

      return res
    },
    onSubmit() {
      const item = { ...this.item }
      if (item.change_routes === 1 || item.route_names === '*') {
        item.route_names = '*'
      } else {
        item.route_names = JSON.stringify(this.$refs.tree.getCheckedKeys())
      }
      item.change_routes = undefined
      if (this.isEdit) {
        item.updated_at = undefined
        item.created_at = undefined
        updateItem(item).then(res => {
          this.$message.success('修改成功')
          this.$router.back()
          if (item.id === this.$store.getters.role.id) {
            window.location.reload()
          }
        })
      } else if (!this.isDetail) {
        createItem(item).then(res => {
          this.$message.success('创建成功')
          this.$router.back()
        })
      } else {
        this.$router.back()
      }
    },
    onCancel() {
      this.$router.back()
    }
  }
}
</script>
<style>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }

  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }

  .avatar {
    width: 178px;
    display: block;
  }
  .admin-role .el-tree{
    width: 50%;
  }
</style>
<style scoped>

</style>
