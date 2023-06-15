<template>
  <div>
    <el-card class="list-query" shadow="hover">
      <el-form inline size="small" label-width="60px">
        <el-form-item label="昵称">
          <el-input v-model="listQuery.nickname"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="handlerQuery">筛选</el-button>
          <el-button type="danger" @click="toAdd">添加</el-button>
        </el-form-item>
      </el-form>
    </el-card>
    <el-card class="list-body" shadow="hover">
      <el-table :data="listRes.list" v-loading="listRes.loading" border size="small">
        <el-table-column prop="id" label="id" align="center"></el-table-column>
        <el-table-column prop="nickname" label="昵称" align="center"></el-table-column>
        <el-table-column prop="created_at" label="创建时间" align="center"></el-table-column>
        <el-table-column label="操作" align="center">
          <template #default="{row}">
            <el-button size="small" @click="toEdit(row)">编辑</el-button>
            <el-button size="small" @click="changePass(row)">重置密码</el-button>
            <el-button size="small" type="danger" @click="remove(row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
    <el-card class="list-page" shadow="hover">
      <el-pagination background
                     layout="prev, pager, next, sizes, jumper"
                     :page-sizes="[1,10,20,50,100]"
                     v-model:page-size="listQuery.page_size"
                     v-model:current-page="listQuery.page"
                     :total="listRes.total">
      </el-pagination>
    </el-card>
  </div>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { actions, useRepositories } from '@/views/admin/composables'
import { useRouter } from 'vue-router'
//列表
const {
  listRes,
  listQuery,
  handlerQuery,
  getList,
} = useRepositories()

onMounted(getList)

watch(() => listQuery.page, getList)

watch(() => listQuery.page_size, () => listQuery.page > 1 ? (listQuery.page = 1) : getList())

const { changePass, del } = actions()
//删除
const remove = async (row) => {
  const res = await del(row.id)
  if (res) {
    getList(listQuery)
  }
}

const router = useRouter()
const toEdit = (row) => {
  router.push('/admin/edit/' + row.id)
}
const toAdd = () => {
  router.push('/admin/add')
}

</script>

<style scoped>
.list-body {
  margin-top: 10px;
}

.list-page {
  margin-top: 5px;
}
</style>
