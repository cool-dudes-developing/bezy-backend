<script lang="ts" setup>
import { computed, defineAsyncComponent } from 'vue'
import archiveIcon from '@/assets/svg/archive.svg';
import arrowUpIcon from '@/assets/svg/arrow-up.svg';
import browserIcon from '@/assets/svg/browser.svg';
import chevronLeftIcon from '@/assets/svg/chevron-left.svg';
import clockIcon from '@/assets/svg/clock.svg';
import closeIcon from '@/assets/svg/close.svg';
import codeIcon from '@/assets/svg/code.svg';
import copyIcon from '@/assets/svg/copy.svg';
import cursorIcon from '@/assets/svg/cursor.svg';
import databaseIcon from '@/assets/svg/database.svg';
import debugIcon from '@/assets/svg/debug.svg';
import endpointsIcon from '@/assets/svg/endpoints.svg';
import externalIcon from '@/assets/svg/external.svg';
import eyeIcon from '@/assets/svg/eye.svg';
import fileIcon from '@/assets/svg/file.svg';
import fileArchiveIcon from '@/assets/svg/file-archive.svg';
import fileAudioIcon from '@/assets/svg/file-audio.svg';
import fileDirectory24Icon from '@/assets/svg/file-directory-24.svg';
import fileDownIcon from '@/assets/svg/file-down.svg';
import fileImageIcon from '@/assets/svg/file-image.svg';
import filePenIcon from '@/assets/svg/file-pen.svg';
import filePlusIcon from '@/assets/svg/file-plus.svg';
import fileTextIcon from '@/assets/svg/file-text.svg';
import fileVideoIcon from '@/assets/svg/file-video.svg';
import filesIcon from '@/assets/svg/files.svg';
import fitIcon from '@/assets/svg/fit.svg';
import folderIcon from '@/assets/svg/folder.svg';
import folderBigIcon from '@/assets/svg/folder-big.svg';
import folderOpenIcon from '@/assets/svg/folder-open.svg';
import folderPlusIcon from '@/assets/svg/folder-plus.svg';
import heartIcon from '@/assets/svg/heart.svg';
import helpIcon from '@/assets/svg/help.svg';
import imageIcon from '@/assets/svg/image.svg';
import logOutIcon from '@/assets/svg/log-out.svg';
import logicIcon from '@/assets/svg/logic.svg';
import logoIcon from '@/assets/svg/logo.svg';
import logoLightIcon from '@/assets/svg/logo-light.svg';
import logoMonochromeIcon from '@/assets/svg/logo-monochrome.svg';
import logoMonochromeInvIcon from '@/assets/svg/logo-monochrome-inv.svg';
import packageIcon from '@/assets/svg/package.svg';
import pencilRulerIcon from '@/assets/svg/pencil-ruler.svg';
import peopleIcon from '@/assets/svg/people.svg';
import playIcon from '@/assets/svg/play.svg';
import plusIcon from '@/assets/svg/plus.svg';
import plus24Icon from '@/assets/svg/plus-24.svg';
import plusSmallBlueIcon from '@/assets/svg/plus-small-blue.svg';
import projectIcon from '@/assets/svg/project.svg';
import questionIcon from '@/assets/svg/question.svg';
import redoIcon from '@/assets/svg/redo.svg';
import repoIcon from '@/assets/svg/repo.svg';
import restoreIcon from '@/assets/svg/restore.svg';
import saveIcon from '@/assets/svg/save.svg';
import searchIcon from '@/assets/svg/search.svg';
import sendIcon from '@/assets/svg/send.svg';
import tableIcon from '@/assets/svg/table.svg';
import trashcanIcon from '@/assets/svg/trashcan.svg';
import undoIcon from '@/assets/svg/undo.svg';
import uploadIcon from '@/assets/svg/upload.svg';

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
})

const icon = computed(() => {
    switch (props.name) {
        case 'archive':
            return archiveIcon
        case 'arrow-up':
            return arrowUpIcon
        case 'browser':
            return browserIcon
        case 'chevron-left':
            return chevronLeftIcon
        case 'clock':
            return clockIcon
        case 'close':
            return closeIcon
        case 'code':
            return codeIcon
        case 'copy':
            return copyIcon
        case 'cursor':
            return cursorIcon
        case 'database':
            return databaseIcon
        case 'debug':
            return debugIcon
        case 'endpoints':
            return endpointsIcon
        case 'external':
            return externalIcon
        case 'eye':
            return eyeIcon
        case 'file':
            return fileIcon
        case 'file-archive':
            return fileArchiveIcon
        case 'file-audio':
            return fileAudioIcon
        case 'file-directory-24':
            return fileDirectory24Icon
        case 'file-down':
            return fileDownIcon
        case 'file-image':
            return fileImageIcon
        case 'file-pen':
            return filePenIcon
        case 'file-plus':
            return filePlusIcon
        case 'file-text':
            return fileTextIcon
        case 'file-video':
            return fileVideoIcon
        case 'files':
            return filesIcon
        case 'fit':
            return fitIcon
        case 'folder':
            return folderIcon
        case 'folder-big':
            return folderBigIcon
        case 'folder-open':
            return folderOpenIcon
        case 'folder-plus':
            return folderPlusIcon
        case 'heart':
            return heartIcon
        case 'help':
            return helpIcon
        case 'image':
            return imageIcon
        case 'log-out':
            return logOutIcon
        case 'logic':
            return logicIcon
        case 'logo':
            return logoIcon
        case 'logo-light':
            return logoLightIcon
        case 'logo-monochrome':
            return logoMonochromeIcon
        case 'logo-monochrome-inv':
            return logoMonochromeInvIcon
        case 'package':
            return packageIcon
        case 'pencil-ruler':
            return pencilRulerIcon
        case 'people':
            return peopleIcon
        case 'play':
            return playIcon
        case 'plus':
            return plusIcon
        case 'plus-24':
            return plus24Icon
        case 'plus-small-blue':
            return plusSmallBlueIcon
        case 'project':
            return projectIcon
        case 'question':
            return questionIcon
        case 'redo':
            return redoIcon
        case 'repo':
            return repoIcon
        case 'restore':
            return restoreIcon
        case 'save':
            return saveIcon
        case 'search':
            return searchIcon
        case 'send':
            return sendIcon
        case 'table':
            return tableIcon
        case 'trashcan':
            return trashcanIcon
        case 'undo':
            return undoIcon
        case 'upload':
            return uploadIcon
        default:
            return null
    }
})
</script>

<template>
    <component
        :is="icon"
    />
</template>
