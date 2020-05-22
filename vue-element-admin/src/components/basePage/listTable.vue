<template>
  <el-table
    :ref="refId"
    :key="tableKey"
    v-loading="listLoading"
    :data="afterList"
    :default-expand-all="defaultExpandAll"
    border
    fit
    highlight-current-row
    @sort-change="sortChange"
    @selection-change="selectionChange"
  >
    <!--selectionChange el-table 的事件，选择器发生变化时触发  -->
    <!--sortChange el-table 的事件，排序触发  -->
    <!--handleButtonClick 绑定 按钮点击事件, mergeActions 和 tableColumns[n]['type']为button时 点击触发 -->
    <!--handleSelectChange 绑定 select选择变化事件  -->
    <!--handleUpdate 操作中 点击修改触发-->
    <!--handleDetail 操作中 点击详情触发-->
    <!--handleDelete 操作中 点击删除触发-->

    <!-- 操作中更多操作,点击触发handleButtonClick
    mergeActions=[
      {label:'操作1',type:'warning',prop:'id'},
      {label:'操作2',type:'primary',prop:'name'}
    ]-->
    <!--
    tableColumns=[
      {label:'ID', prop:'id',type:'selection'},
      {label:'姓名', prop:'name',width:'100px',className:'class',align:'center',sortable:true},
      {label:'创建时间', prop:'created_at'},
      {label:'子对象', prop:'child.id'},
      {label:'子列表对象', prop:'child.0.id'},
      {label:'状态', prop:'status',type:'enum',options:{1:'值1',2:'值2'} },
      {label:'状态', prop:'status',type:'select',options:[{label:'状态1',value:1},{label:'状态2',value:2}] },
      {label:'状态', prop:'status',type:'tag',options:[{ value: 0, label: '删除', type: 'danger' }, { value: 1, label: '禁用', type: 'info' }, { value: -1, label: '启用', type: 'success' }]},
      {label:'状态', prop:'status',type:'button',options:{type:'primary',label:'按钮1'}},
      {label:'html', prop:'html',type:'html'},
    ]-->
    <template v-for="(columnItem,index) in tableColumns">
      <!--普通 情况 定义-->
      <el-table-column
        v-if="!getType(columnItem.type, null) || getType(columnItem.type, null) ==='selection'"
        :key="index"
        :label="columnItem.label"
        :prop="columnItem.prop"
        :class-name="columnItem.className"
        :width="columnItem.width"
        :align="columnItem.align ? columnItem.align : 'center' "
        :sortable="columnItem.sortable ? columnItem.sortable : false"
        :type="getType(columnItem.type, null)||''"
        :formatter="columnItem.formatter||null"
      />
      <slot v-else-if="getType(columnItem.type, null) ==='slot'" :name="columnItem.prop" />
      <el-table-column
        v-else
        :key="index"
        :label="columnItem.label"
        :prop="columnItem.prop"
        :class-name="columnItem.className"
        :width="columnItem.width"
        :align="columnItem.align ? columnItem.align : 'center' "
        :sortable="columnItem.sortable ? columnItem.sortable : false"
        :type="['index','expand'].indexOf(getType(columnItem.type, null)) > -1?getType(columnItem.type, null):''"
      >
        <template slot-scope="{ row, column, $index }">
          <template v-if="getType(columnItem.type,row[columnItem.prop],row, column) ==='enum'">
            <!--选项值 options:{1:'值1',2:'值2'}-->{{ columnItem.options[row[columnItem.prop]] }}
          </template>
          <template v-if="getType(columnItem.type,row[columnItem.prop],row, column) ==='select'">
            <!--下拉框 options:[{label:'选择1',value:1},{label:'选择2',value:2}]-->
            <el-select
              v-model="row[columnItem.prop]"
              :placeholder="columnItem.className"
              :disabled="columnItem.disabled"
              :multiple="columnItem.multiple?true:false"
              @change="handleSelectChange({val:$event,prop:columnItem.prop,row})"
            >
              <el-option
                v-for="item in columnItem.options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </template>
          <template v-if="getType(columnItem.type,row[columnItem.prop],row, column) ==='tag'">
            <!--标签   options: {[ {type:'info',label:'失败',value:1}, {type:'success',label:'成功',value:2} ]}-->
            <!--标签   options: {[ {label:'失败',value:1},{type:'success',label:'成功',value:2} ]}-->
            <template v-if="columnItem.options && columnItem.options.length">
              <template v-for="(item,key) in columnItem.options">
                <el-tag v-if="item.value==row[columnItem.prop]" :key="key" :type="item.type||'info'">{{ item.label }}</el-tag>
              </template>
            </template>
            <!--标签   options如果不定义或找不到对应的值 则直接展示值并且默认type为info-->
            <el-tag v-else type="info">{{ row[columnItem.prop] }}</el-tag>
          </template>
          <template v-if="getType(columnItem.type,row[columnItem.prop],row, column) ==='button'">
            <!--按钮 options:{type:'primary',label:'按钮1'}-->
            <el-button :type="columnItem.options ? columnItem.options.type||'primary' : 'primary'" @click="handleButtonClick({val:$event,prop:columnItem.prop,row})">
              {{ columnItem.options ? columnItem.options.label||'按钮' : '' }}
            </el-button>
          </template>
          <template v-if="getType(columnItem.type,row[columnItem.prop],row, column) ==='html'">
            <div v-html="row[columnItem.prop]" />
          </template>
          <template v-if="getType(columnItem.type,row[columnItem.prop],row, column) ==='img'">
            <img :src="row[columnItem.prop]" style="max-width: 100px;max-height: 100px;">
          </template>
          <template v-if="getType(columnItem.type,row[columnItem.prop],row, column) ==='editInput'">
            <el-input v-model="row[columnItem.prop]" :value="row[columnItem.prop]">
              <el-button slot="append" icon="el-icon-check" @click="saveEditInput({ prop:columnItem.prop, row, column })" />
            </el-input>
          </template>

        </template>
      </el-table-column>
    </template>
    <!--    操作之前的插槽-->
    <slot name="beforeActionsColumn" />
    <el-table-column v-if="actions" label="操作" align="center" :width="(actionsUpdate+actionsDetail+actionsDelete+mergeActions.length) * 100 + 'px'">
      <template slot-scope="{row}">
        <el-button v-if="actionsUpdate && !row.noUpdate" type="primary" @click="handleUpdate(row)">
          修改
        </el-button>
        <el-button v-if="actionsDetail && !row.noDetail" type="success" @click="handleDetail(row)">
          详情
        </el-button>
        <el-button v-if="actionsDelete && !row.noDelete" type="danger" @click="handleDelete(row)">
          删除
        </el-button>
        <el-button v-for="(item,index) in mergeActions" :key="index" :type="item.type" @click="handleButtonClick({prop:item.prop,row})">
          {{ item.label }}
        </el-button>
      </template>
    </el-table-column>
    <!--    最后的插槽-->
    <slot name="lastColumn" />
  </el-table>
</template>

<script>
export default {
  name: 'ListTable',
  props: {
    mergeActions: {
      type: Array,
      default() {
        return []
      }
    },
    // 是否显示修改按钮
    actionsUpdate: {
      type: Boolean,
      default: true
    },
    // 是否显示详情按钮
    actionsDetail: {
      type: Boolean,
      default: true
    },
    // 是否显示删除按钮
    actionsDelete: {
      type: Boolean,
      default: true
    },
    // 是否显示操作列
    actions: {
      type: Boolean,
      default: true
    },
    defaultExpandAll: {
      type: Boolean,
      default: false
    },
    tableKey: {
      type: String,
      default: ''
    },
    listLoading: {
      type: Boolean,
      default: true
    },
    list: {
      type: Array,
      default() {
        return []
      }
    },
    refId: {
      type: String,
      default: 'listTable'
    },
    tableColumns: {
      type: Array,
      default() {
        return []
      }
    }
  },
  computed: {
    afterList: {
      get() {
        const list = [...this.list]
        const reList = []
        list.forEach((item, index) => {
          if (typeof item === 'object') {
            for (const key in item) {
              this.transferListObject(item[key], key, item)
            }
          }
          reList.push(item)
        })
        // console.log(reList)
        return reList
      }
    }
  },
  methods: {
    // 将子对象的值转化到父对象中
    transferListObject(object, keyLabel, baseObj) {
      if (typeof object === 'object') {
        for (const key in object) {
          if (typeof object[key] === 'object') {
            this.transferListObject(object[key], keyLabel + '.' + key, baseObj)
          } else {
            baseObj[keyLabel + '.' + key] = object[key]
          }
        }
      }
    },
    handleUpdate(row) {
      this.$emit('handleUpdate', row)
    },
    handleDetail(row) {
      this.$emit('handleDetail', row)
    },
    handleDelete(row) {
      this.$emit('handleDelete', row)
    },
    sortChange({ column, prop, order }) {
      this.$emit('sortChange', { column, prop, order })
    },
    selectionChange(selected) {
      this.$emit('selectionChange', selected)
    },
    handleSelectChange({ val, prop, row }) {
      this.$emit('handleSelectChange', { val, prop, row })
    },
    handleButtonClick({ prop, row }) {
      this.$emit('handleButtonClick', { prop, row })
    },
    saveEditInput({ prop, row, column }) {
      this.$emit('saveEditInput', { prop, row, column })
    }, /* saveEditInput({ prop, row, column }) {
      const item = { id: row.id }
      item[prop] = row[prop]
      updateItem(item).then(res => {
        this.$message.success(res.msg)
      })
    },*/

    getType(type, value, row = undefined, prop = undefined) {
      if (typeof type === 'string') {
        return type
      }
      if (typeof type === 'function') {
        return type(value, row, prop)
      }
    }
  }
}
</script>

<style scoped>

</style>
