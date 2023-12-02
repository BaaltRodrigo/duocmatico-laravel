import {
  browserLocalPersistence,
  GoogleAuthProvider,
  setPersistence,
  signInWithPopup,
  signOut,
  signInWithEmailAndPassword,
  createUserWithEmailAndPassword,
  sendPasswordResetEmail,
} from "firebase/auth";
import { auth } from "../config/firebase";
import axios from "axios";

const state = {
  user: null,
  token: null,
  roles: [],
  permissions: [],
};

const getters = {
  user(state) {
    return state.user;
  },
};

const mutations = {
  setRoles(state, roles) {
    state.roles = roles;
  },

  setPermissions(state, permissions) {
    state.permissions = permissions;
  },

  setUser(state, user) {
    state.user = user;
  },

  setToken(state, token) {
    state.token = token;
  },
};

const actions = {
  async checkUser({ commit, dispatch }) {
    await setPersistence(auth, browserLocalPersistence);
    const user = auth.currentUser;

    if (!user) return; // early exit

    commit("setUser", user);

    await dispatch("getCurrentRolesAndPermission");
  },

  async getCurrentRolesAndPermission({ commit, rootState }) {
    const token = await auth.currentUser?.getIdToken();
    const response = await axios.get(`${rootState.apiUrl}/auth/me`, {
      headers: {
        Authorization: `Bearer ` + token,
      },
    });

    const { roles, permissions } = response.data;

    commit("setRoles", roles);
    commit("setPermissions", permissions);
    return response.data
  },

  async loginWhitGoogle({ commit }) {
    try {
      const provider = new GoogleAuthProvider();
      await setPersistence(auth, browserLocalPersistence);
      const response = await signInWithPopup(auth, provider);

      commit("setUser", response.user);
    } catch (error) {
      console.log(error);
    }
  },

  async login({ commit }, { email, password }) {
    try {
      await setPersistence(auth, browserLocalPersistence);
      const response = await signInWithEmailAndPassword(auth, email, password);

      commit("setUser", response.user);
    } catch (error) {
      console.error(error);
    }
  },

  async logout({ commit }) {
    await signOut(auth);
    // Clean auth status
    commit("setUser", null);

    // Clean another modules state if needed
    commit("calendars/setApiCalendars", [], { root: true });
  },

  async registration({ commit }, payload) {
    await setPersistence(auth, browserLocalPersistence);

    const { email, password, passwordConfirmation } = payload;
    if (password !== passwordConfirmation) {
      throw new Error("Passwords do not match");
    }

    const response = await createUserWithEmailAndPassword(
      auth,
      email,
      password
    );

    commit("setUser", response.user);
  },

  async requestPasswordReset({ commit }, email) {
    try {
      const resetResponse = await sendPasswordResetEmail(auth, email);
      return resetResponse;
    } catch (error) {
      console.log(error);
      return null;
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
