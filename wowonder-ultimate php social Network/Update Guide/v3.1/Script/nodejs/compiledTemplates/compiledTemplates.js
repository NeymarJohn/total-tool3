let Handlebars = require("handlebars")
let fs = require("fs")
let chatList = fs.readFileSync('./templates/chat-list.html');
let groupList = fs.readFileSync('./templates/group-list.html');
let offlineUser = fs.readFileSync('./templates/offline-user.html');
let onlineUser = fs.readFileSync('./templates/online-user.html');
let messageList = fs.readFileSync('./templates/message-text-list.html');
let messageGroupRecipientsList = fs.readFileSync('./templates/messages-group-list.html');
let messageRecipientsList = fs.readFileSync('./templates/messages-recipients-list.html');

// let notification = fs.readFileSync('./notification.html');

const chatListTemplate = Handlebars.compile(chatList.toString());
const groupListTemplate = Handlebars.compile(groupList.toString());
const messageListTemplate = Handlebars.compile(messageList.toString());
const offlineUserTemplate = Handlebars.compile(offlineUser.toString());
const onlineUserTemplate = Handlebars.compile(onlineUser.toString());
const messageGroupRecipientsTemplate = Handlebars.compile(messageGroupRecipientsList.toString());
const messageRecipientsTemplate = Handlebars.compile(messageRecipientsList.toString());


const funcs = require('../functions/functions');
const { group } = require("console");

module.exports.messageRecipientsTemplate = async (ctx, recipientUserId, isActive, isOnline, count_messages, messageText) => {
    let user = await funcs.Wo_UserData(ctx, recipientUserId)
    if (user) {
        let a = messageRecipientsTemplate({
            active: isActive,
            recipientUserId: user.user_id,
            recipientName: user.name,
            recipientAvatar: await funcs.Wo_GetMedia(ctx, user.avatar),
            online: isOnline,
            message_count_is_zero: count_messages == 0,
            message_count: count_messages,
            elapased_time: (messageText.time ? funcs.Wo_Time_Elapsed_String(ctx, messageText.time) : ''),
            messageText: messageText.text || "",
        })
        return a
    } else {
        console.error("No user found undefined")
        return ""
    }
}


module.exports.messageGroupRecipientsTemplate = async (ctx, groupId, groupName, groupAvatar, isActive, messageText) => {
    let a = messageGroupRecipientsTemplate({
        active: isActive,
        pull_left_right: "pull_right",
        groupName: groupName,
        groupId: groupId,
        groupAvatar: await funcs.Wo_GetMedia(ctx, groupAvatar),
        time: (messageText.time ? funcs.Wo_Time_Elapsed_String(ctx, messageText.time) : ''),
        messageText: messageText.text || "",
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
    return a
}

module.exports.onlineUserTemplate = async (ctx, onlineUser, count_messages) => {
    return onlineUserTemplate({
        chat_list_user_id: onlineUser.user_id,
        chat_list_name: (await funcs.Wo_UserData(ctx, onlineUser.user_id)).name,
        chat_list_avatar: await funcs.Wo_GetMedia(ctx, onlineUser.avatar),
        is_message_count_zero: count_messages == 0,
        message_count_per_user: count_messages,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}

module.exports.offlineUserTemplate = async (ctx, offlineUser, count_messages) => {
    return offlineUserTemplate({
        chat_list_user_id: offlineUser.user_id,
        chat_list_name: (await funcs.Wo_UserData(ctx, offlineUser.user_id)).name,
        chat_list_avatar: await funcs.Wo_GetMedia(ctx, offlineUser.avatar),
        is_message_count_zero: ctx.globalconfig["user_lastseen"] === '1' && offlineUser.showlastseen !== '0',
        message_count_per_user: count_messages,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}

module.exports.chatListOwnerFalse = async (ctx, data, fromUser, nextId, hasHTML, sendable_message) => {
    return chatListTemplate({
        onwer: false,
        chatmsgId: "" + nextId,
        username: data.username,
        rightLeft: "",
        avatar: await funcs.Wo_GetMedia(ctx, fromUser.avatar),
        backgroundColor: "",
        color: "",
        media: false,
        chatTxt: sendable_message,
        hasHTML: hasHTML,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}

module.exports.chatListOwnerTrue = async (ctx, data, fromUser, nextId, hasHTML, sendable_message, color) => {
    return chatListTemplate({
        onwer: true,
        chatmsgId: "" + nextId,
        username: data.username,
        rightLeft: "",
        avatar: "",
        hasHTML: hasHTML,
        media: false,
        backgroundColor: color,
        color: "rgb(255, 255, 255)",
        chatTxt: sendable_message,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}


module.exports.chatListOwnerTrueWithMedia = async (ctx, data, fromUser, nextId, hasHTML,  color, isSticker) => {
    return chatListTemplate({
        onwer: true,
        chatmsgId: "" + nextId,
        username: data.username,
        rightLeft: "",
        avatar: "",
        media: true,
        mediaHTML: await funcs.Wo_DisplaySharedFile(ctx, data.mediaId, 'chat', isSticker),
        backgroundColor: color,
        color: "rgb(255, 255, 255)",
        chatTxt: "",
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}

module.exports.chatListOwnerFalseWithMedia = async (ctx, data, fromUser, nextId, hasHTML, isSticker) => {
    return chatListTemplate({
        onwer: false,
        chatmsgId: "" + nextId,
        username: data.username,
        rightLeft: "",
        avatar: await funcs.Wo_GetMedia(ctx, fromUser.avatar),
        backgroundColor: "",
        color: "",
        media: (data.media_data && data.media_link) || data.isSticker ? true : false,
        mediaHTML: await funcs.Wo_DisplaySharedFile(ctx, data.mediaId, 'chat', isSticker),
        chatTxt: data.msg,
        hasHTML: hasHTML,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}

module.exports.messageListOwnerTrue = async (ctx, data, fromUser, message, hasHTML, sendable_message, color) => {
    if (message && message.time && message.id && message.time != '' && message.id != '') {
        nextId = message.id;
        timeText = funcs.Wo_Time_Elapsed_String(ctx, message.time);
        time = message.time;
    }
    else{
        nextId = message;
        timeText = 'Just now';
        time = Math.floor(Date.now() / 1000);
    }
    return messageListTemplate({
        onwer: true,
        chatMsgId: "" + nextId,
        username: data.username,
        avatar: await funcs.Wo_GetMedia(ctx, fromUser.avatar),
        hasHTML: hasHTML,
        backgroundColor: color,
        color: "rgb(255, 255, 255)",
        msgColor: "rgb(168, 72, 73)",
        chatTxt: sendable_message,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder",
        msgTime: time,
        ElapsedTime: timeText
    })
}


module.exports.messageListOwnerTrueWithMedia = async (ctx, data, fromUser, message, hasHTML, color) => {
    if (message && message.time && message.id && message.time != '' && message.id != '') {
        nextId = message.id;
        timeText = funcs.Wo_Time_Elapsed_String(ctx, message.time);
        time = message.time;
    }
    else{
        nextId = message;
        timeText = 'Just now';
        time = Math.floor(Date.now() / 1000);
    }
    return messageListTemplate({
        onwer: true,
        chatMsgId: "" + nextId,
        username: data.username,
        avatar: await funcs.Wo_GetMedia(ctx, fromUser.avatar),
        backgroundColor: color,
        hasHTML: hasHTML,
        color: "rgb(255, 255, 255)",
        chatTxt: "",
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder",
        mediaHTML: await funcs.Wo_DisplaySharedFile(ctx, data.mediaId, 'message'),
        msgTime: time,
        ElapsedTime: timeText
    })
}

module.exports.messageListOwnerFalse = async (ctx, data, message, fromUser, hasHTML, sendable_message) => {
    //funcs.Wo_Time_Elapsed_String(ctx, Math.floor(Date.now() / 1000))
    if (message && message.time && message.id && message.time != '' && message.id != '') {
        nextId = message.id;
        timeText = funcs.Wo_Time_Elapsed_String(ctx, message.time);
        time = message.time;
    }
    else{
        nextId = message;
        timeText = 'Just now';
        time = Math.floor(Date.now() / 1000);
    }

    return messageListTemplate({
        onwer: false,
        chatMsgId: "" + nextId,
        username: data.username,
        avatar: await funcs.Wo_GetMedia(ctx, (fromUser.avatar) ? fromUser.avatar : 1),
        backgroundColor: "",
        color: "",
        chatTxt: sendable_message,
        hasHTML: hasHTML,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder",
        msgTime: time,
        ElapsedTime: timeText
    })
}


module.exports.messageListOwnerFalseWithMedia = async (ctx, data, message, fromUser, isSticker) => {
    if (message && message.time && message.id && message.time != '' && message.id != '') {
        nextId = message.id;
        timeText = funcs.Wo_Time_Elapsed_String(ctx, message.time);
        time = message.time;
    }
    else{
        nextId = message;
        timeText = 'Just now';
        time = Math.floor(Date.now() / 1000);
    }
    return messageListTemplate({
        onwer: false,
        chatMsgId: "" + nextId,
        username: data.username,
        avatar: await funcs.Wo_GetMedia(ctx, fromUser.avatar),
        backgroundColor: "",
        color: "",
        chatTxt: "",
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder",
        mediaHTML: await funcs.Wo_DisplaySharedFile(ctx, data.mediaId, 'message', isSticker),
        msgTime: time,
        ElapsedTime: timeText
    })
}




module.exports.groupListOwnerTrue = async (ctx, messageOwner, nextId, data, hasHTML, sendable_message) => {
    return groupListTemplate({
        onwer: true,
        chatmsgId: "" + nextId,
        chatMsgTxt: sendable_message,
        hasHTML: hasHTML,
        username: messageOwner.username,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}


module.exports.groupListOwnerTrueWithMedia = async (ctx, messageOwner, nextId, data, sendable_message, isSticker) => {
    return groupListTemplate({
        onwer: true,
        chatmsgId: "" + nextId,
        chatMsgTxt: "",
        username: messageOwner.username,
        mediaHTML: await funcs.Wo_DisplaySharedFile(ctx, data.mediaId, 'chat', isSticker),
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}

module.exports.groupListOwnerFalse = async (ctx, messageOwner, nextId, data, hasHTML, sendable_message) => {
    return groupListTemplate({
        chatMsgId: "" + nextId,
        onwer: false,
        chatMsgTxt: sendable_message,
        hasHTML: hasHTML,
        username: messageOwner.username,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}


module.exports.groupListOwnerFalseWithMedia = async (ctx, messageOwner, nextId, data, sendable_message, isSticker) => {
    return groupListTemplate({
        chatMsgId: "" + nextId,
        onwer: false,
        chatMsgTxt: "",
        mediaHTML: await funcs.Wo_DisplaySharedFile(ctx, data.mediaId, 'chat', isSticker),
        username: messageOwner.username,
        wowonderTheme: ctx.globalconfig['theme'] === "wowonder"
    })
}