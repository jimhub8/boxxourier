const state = {
    userList = {}
} 

const mutations = {
    SET_USER_LIST = (state, userList) {
        state.userList = userList
    }
} 

const actions = {
    setUserList = ({commit}, userList) => {

    }
} 

export default {
    state, mutations, actions
}