<template>
  <div class="app-container">
    <div class="filter-container">
      <listQueryForm
        :value.sync="listQuery"
        :form-options="listQueryFormOptions"
        @handleFilter="handleFilter"
        @handleCreate="handleCreate"
      >
        <template slot="otherButton">
          <el-button type="success" icon="el-icon-plus" @click="handleCreate">
            添加
          </el-button>
        </template>
      </listQueryForm>
    </div>
    <listTable
      :list-loading="listLoading"
      :list="list"
      :table-columns="tableColumns"
      @handleUpdate="handleUpdate"
      @handleDetail="handleDetail"
      @handleDelete="handleDelete"
      @handleSelectChange="handleSelectChange"
    />
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.page_size" :page-sizes="[10, 20, 30, 50]" @pagination="getList" />
  </div>
</template>

<script>
import { fetchList, deleteItem } from '@/api/admin'
import listQueryForm from '@/components/basePage/listQueryForm'
import listTable from '@/components/basePage/listTable'
import pagination from '@/components/Pagination'
import { commonOptions } from '../../utils/options'

const model = 'admin'
export default {
  name: `${model}List`,
  components: { pagination, listQueryForm, listTable },
  data() {
    return {
      listQueryFormOptions: [
        { label: '昵称', type: 'input', prop: 'nickname' }
      ],
      listLoading: true,
      total: 0,
      listQuery: {
        page: 1,
        page_size: 10
      },
      list: [],
      tableColumns: [
        { label: 'ID', prop: 'id', width: '50px' },
        { label: '角色', prop: 'role.name' },
        { label: '昵称', prop: 'nickname' },
        { label: '头像', prop: 'avatar', type: 'img' },
        { label: '状态', prop: 'status', type: 'tag', options: commonOptions.status },
        { label: '手机号', prop: 'telephone' },
        { label: 'QQ', prop: 'qq' }
      ]
    }
  },
  created() {
    this.getList()
  },
  methods: {
    handleCreate() {
      this.$router.push(`/${model}/add`)
    },
    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.list
        this.total = response.data.total
        this.listLoading = false
      })
    },
    handleSelectChange({ val, row, prop }) {
      // updateStatus({ id: row.id, status: row.status }).then(res => {
      //   this.$message.success('修改成功')
      //   this.getList()
      // })
    },
    handleUpdate(row) {
      this.$router.push(`/${model}/edit/${row.id}`)
    },
    handleDetail(row) {
      this.$router.push(`/${model}/detail/${row.id}`)
    },
    handleDelete(row) {
      this.$msgbox.confirm('确定删除么？', '删除', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warn'
      }).then(() => {
        deleteItem({ id: row.id }).then(res => {
          this.$message.success('删除成功')
          this.getList()
        })
      }, () => {
        console.log('取消')
      })
    },
    handleFilter() {
      console.log('handleFilter', this.listQuery)
      this.getList()
    }
  }
}
</script>

<style scoped>

</style>
