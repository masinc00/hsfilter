<template>
    <div>
        <input v-model="text" type="text">
        <div id="filterbar-loading"><img src="loading.gif"></div>
        <ul id="filterbar-result">
            <li v-for="item in json" v-bind:key="item.id"> {{ item.name_enus}} </li>
        </ul>
    </div>
</template>

<style>
    #filterbar-loading{
        display: none;
    }
</style>
