<template>
  <div class="upload-order-file">
    <el-upload
      ref="upload"
      :on-success="fileUploadSuccess"
      :before-upload="beforeFileUpload"
      :on-remove="fileRemove"
      :on-change="fileChange"
      name="file"
      multiple
      :file-list="fileList"
      :action="fileUploadHost"
      :data="fileUploadData"
      :on-error="fileUpErr"
      :list-type="listType"
      :limit="limit"
      accept="image/*"
    >
      <slot name="default">
        <i class="el-icon-plus" />
      </slot>
      <template slot="file" slot-scope="{file}">
        <img
          class="el-upload-list__item-thumbnail"
          :src="file.url"
          alt=""
        >
        <label class="el-upload-list__item-status-label">
          <i class="el-icon-upload-success el-icon-check" />
        </label>
        <el-progress
          v-if="file.status === 'uploading'"
          type="circle"
          :stroke-width="6"
          :percentage="parseInt(file.percentage)"
        />
        <span class="el-upload-list__item-actions">
          <span
            class="el-upload-list__item-preview"
            @click="leftImage(file)"
          >
            <i class="el-icon-arrow-left" />
          </span>
          <span
            class="el-upload-list__item-delete"
            @click="removeImage(file)"
          >
            <i class="el-icon-delete" />
          </span>
          <span
            class="el-upload-list__item-delete"
            @click="rightImage(file)"
          >
            <i class="el-icon-arrow-right" />
          </span>
        </span>
      </template>
    </el-upload>
  </div>
</template>

<script>
import { random_filename } from '@/utils/file'
import { getOssToken } from '@/api/file'

export default {
  name: 'UpOrderFile',
  props: {
    limit: {
      type: Number,
      default: 0
    },
    listType: {
      type: String,
      default: 'picture-card'
    },
    beforeUpload: {
      type: Function,
      default: function() {
        return true
      }
    },
    onChange: {
      type: Array,
      default: function() {
        return []
      }
    },
    exImages: {
      type: Array,
      default: function() {
        return []
      }
    },
    uploadedImages: {
      type: Array,
      default: function() {
        return []
      }
    }
  },
  data() {
    return {
      fileUploadHost: 'http://sku-images.oss-cn-beijing.aliyuncs.com',
      fileUploadData: {},
      fileExpire: 0,
      fileList: []
    }
  },
  computed: {
  },
  watch: {
    exImages(val) {
      this.fileList = val.map(image => { return { url: image } })
    },
    fileList(val) {
      console.log('watch fileList', val)
      this.$nextTick(_ => {
        this.$emit('update:uploadedImages', val.filter(file => file.status === 'success').map(file => file.response ? this.fixImagePath(file.response.data.filename) : file.url))
      })
    }
  },
  created() {
  },
  methods: {
    fixImagePath(filename) {
      return this.fileUploadHost + '/' + filename
    },
    async beforeFileUpload(file) {
      console.log('beforeFileUpload', file)
      if (this.beforeUpload) {
        const br = await this.beforeUpload(file)
        if (!br) { return Promise.reject() }
      }
      // const filename = this.getFileName() + get_suffix(file.name)
      const now = Date.parse(new Date()) / 1000
      if (this.fileExpire < now) {
        await getOssToken().then(res => {
          const obj = res.data
          this.fileExpire = parseInt(obj['expire'])
          this.fileUploadData = {
            // 'key': obj['dir'] + filename,
            'policy': obj['policy'],
            'OSSAccessKeyId': obj['accessid'],
            'success_action_status': '200', // 让服务端返回200,不然，默认会返回204
            'callback': obj['callback'],
            'signature': obj['signature'],
            'x:dir': obj['dir']
          }
          this.fileUploadHost = obj['host']
        })
      }
      await new Promise(resolve => {
        setTimeout(() => { resolve() }, 10)
      })
      this.fileUploadData.key = this.fileUploadData['x:dir'] + random_filename(file.name)
      this.fileUploadData['x:origin_filename'] = file.name
      return Promise.resolve()
    },
    fileUpErr() {

    },
    fileChange(file, fileList) {
      this.fileList = fileList
    },
    fileUploadSuccess(response, file, fileList) {
      this.$emit('onSuccess', response, file, fileList)
    },
    fileRemove(file, fileList) {
      console.log('fileRemove')
      this.$emit('onRemove', file, fileList)
    },
    leftImage(file) {
      const index = this.fileList.findIndex(f => f.uid === file.uid)
      if (index === 0) {
        return
      }
      this.fileList[index] = this.fileList.splice(index - 1, 1, this.fileList[index])[0]
    },
    rightImage(file) {
      const index = this.fileList.findIndex(f => f.uid === file.uid)
      if (index === this.fileList.length - 1) {
        return
      }
      this.fileList[index] = this.fileList.splice(index + 1, 1, this.fileList[index])[0]
    },
    removeImage(file) {
      const index = this.fileList.findIndex(f => f.uid === file.uid)
      this.fileList.splice(index, 1)
      this.$emit('onRemove', file, this.fileList)
    }
  }
}
</script>

<style scoped lang="scss">
  .upload-order-file {
    /deep/ .el-icon-document {
      height: auto;
    }
    .el-upload-list__item-thumbnail{
      object-fit: contain;
    }
  }

</style>
