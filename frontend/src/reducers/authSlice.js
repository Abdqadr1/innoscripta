import { createSlice } from "@reduxjs/toolkit";
import axios from "axios";

const API_URL = import.meta.env.VITE_API_URL;

const initialState = {
  user:  JSON.parse(localStorage.getItem("user") || null),
  token: localStorage.getItem("token") || null,
  loading: false,
  error: null,
};

const authSlice = createSlice({
  name: "auth",
  initialState,
  reducers: {
    loginStart: (state) => { state.loading = true; },
    loginSuccess: (state, action) => {
      state.loading = false;
      state.user = action.payload.user;
      state.token = action.payload.token;
      localStorage.setItem("token", action.payload.token);
      localStorage.setItem("user", JSON.stringify(action.payload.user));
    },
    prefSuccess: (state, action) => {
      state.loading = false;
      state.user = action.payload.data;
      localStorage.setItem("user", JSON.stringify(action.payload.data));
    },
    loginFailure: (state, action) => {
      state.loading = false;
      state.error = action.payload;
    },
    logout: (state) => {
      state.user = null;
      state.token = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    },
  },
});

export const { loginStart, loginSuccess, loginFailure, logout,prefSuccess } = authSlice.actions;

export const login = (credentials, navigate) => async (dispatch) => {
  dispatch(loginStart());
  try {
    const { data } = await axios.post(`${API_URL}/token`, credentials);
    dispatch(loginSuccess(data));
    navigate(data);
  } catch (error) {
    dispatch(loginFailure(error.response.data.message));
  }
};

export const updatePreference = (_, func) => async (dispatch, getState) => {
  const { auth } = getState();

  dispatch(loginStart());
  try {
    const { data } = await axios.get(`${API_URL}/user/preference/toggle`, {
      headers: { Authorization: `Bearer ${auth.token}` },
    });
    dispatch(prefSuccess(data));
    func(data);
  } catch (error) {
    console.log(error);
    if (error.response?.status === 401) {
      thunkAPI.dispatch(logout());
    }
    dispatch(loginFailure(error.response.data.message));
  }
};

export const register = (userData, navigate) => async (dispatch) => {
    dispatch(loginStart());
    try {
      const { data } = await axios.post(`${API_URL}/register`, userData);
      dispatch(loginSuccess(data));
      navigate(data);
    } catch (error) {
      dispatch(loginFailure(error.response.data.message));
    }
  };

export default authSlice.reducer;
