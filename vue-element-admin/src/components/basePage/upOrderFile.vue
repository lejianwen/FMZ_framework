<template>
    <div class="upload-order-file">
        <el-upload
                :on-success="fileUploadSuccess"
                :before-upload="beforeFileUpload"
                name="file"
                ref="upload"
                :file-list="fileList"
                :action="fileUploadHost"
                :multiple="false"
                :data="fileUploadData"
                :on-error="fileUpErr"
                :list-type="listType"
        >
          <slot name="default" />
        </el-upload>
    </div>
</template>

<script>
    import {random_filename} from "@/utils/file";
    import {getOssToken} from '@/api/file'
    export default {
        name: "upOrderFile",
        props:{
            listType:{
                type: String,
                default: 'string'
            }
        },
        data() {
            return {
                fileUploadHost: 'http://sucaiku-jiaogao.oss-cn-beijing.aliyuncs.com',
                fileUploadData: {},
                fileExpire: 0,
                fileList: [],
            }
        },
        methods:{
            async beforeFileUpload(file) {
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
                            'x:dir': obj['dir'],
                            'x:callback': obj['callback']
                        }
                        this.fileUploadHost = obj['host']
                    })
                }
                this.fileUploadData.key = this.fileUploadData['x:dir'] + random_filename(file.name)
            },
            fileUpErr(){

            },
            fileUploadSuccess(response, file, fileList) {
                this.fileList = fileList.slice(-1)
                this.$emit('fileUploadSuccess', response, file, fileList)
            },
        }
    }
</script>

<style scoped lang="scss">
    .upload-order-file{
        /deep/.el-icon-document{
            height: auto;
        }
    }

</style>
