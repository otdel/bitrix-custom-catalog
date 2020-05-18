import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';

@inject('likeStore')
@observer
export default class extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      likes: 0,
      isLiked: false,
      iconButton: "heart",
      tooltip: "",
      colorButton: "",
      isCategory: false,
    }
  }

  componentDidMount() {
    const store = this.props.likeStore;
    const isLiked = this.props.isLiked;
    const likes = this.props.likes;
    const isCategory = this.props.category;

    if (!store.userId) {
      store.setUserId(this.props.userId); // записываем ID пользователя в хранилище
    }

    this.setState((state, props) => ({
      likes: state.likes + Number(likes),
      isCategory: isCategory !== null,
    }));

    if (isLiked === "true") {
      this.paramsLike();
      if (this.state.likes === 0) {
        this.plusLike();
      }
    } else {
      this.paramsDislike();
    }
  }

  paramsLike() {
    this.setState((state) => {
      return {
        isLiked: true,
        iconButton: "heart",
        tooltip: "Убрать из отложенных",
        colorButton: classNames({'uk-icon-button': true, 'uk-margin-small-right': true, 'uk-button-primary': true})
      }
    });
  }

  paramsDislike() {
    this.setState((state) => {
      return {
        isLiked: false,
        iconButton: "heart",
        tooltip: "Отложить",
        colorButton: classNames({'uk-icon-button': true, 'uk-margin-small-right': true})
      }
    });
  }

  addSpinner() {
    this.refs.likeBtn.setAttribute("disabled", "disabled");
    this.refs.likeBtn.setAttribute("uk-spinner", "true");
  }

  removeSpinner() {
    this.refs.likeBtn.removeAttribute("disabled");
    this.refs.likeBtn.classList.remove("uk-spinner");
    this.refs.likeBtn.removeAttribute("uk-spinner");
  }

  handleClick = (e, productId) => {
    e.preventDefault();
    if (this.state.isLiked) {
      this.handleDislike(productId);
    } else {
      this.handleLike(productId)
    }
  }

  plusLike = () => {
    this.setState((state, props) => ({
      likes: state.likes + 1
    }));
  }

  minusLike = () => {
    this.setState((state, props) => ({
      likes: state.likes === 0 ? 0 : state.likes - 1
    }));
  }

  handleLike = (productId) => {
    const store = this.props.likeStore;
    store.like(productId, this.props.isCategory);
    if (store.stateLike === "pending") {
      this.addSpinner();
    }

    const checkStateLiking = () => {
      if (store.stateLike !== "pending") {
        this.removeSpinner()
        if (store.stateLike === "done") {
          this.paramsLike()
          this.plusLike()
        } else { // "error"
          console.log(store.msg)
          store.msg = ""
        }
        clearInterval(checkingInterval);
      }
    }
    const checkingInterval = setInterval(checkStateLiking, 100);
  }

  handleDislike = (productId) => {
    const store = this.props.likeStore;
    store.dislike({id: productId}, this.props.isCategory);
    if (store.stateRemove === "pending") {
      this.addSpinner();
    }
    const checkStateDisabling = () => {
      if (store.stateDislike !== "pending") {
        this.removeSpinner()
        if (store.stateDislike === "done") {
          this.paramsDislike()
          this.minusLike()
        } else { // "error"
          console.log(store.msg);
          store.msg = "";
        }
        clearInterval(checkingInterval);
      }
    }
    const checkingInterval = setInterval(checkStateDisabling, 100);
  }

  render() {
    const store = this.props.likeStore;
    const productId = this.props.productId;

    return (
      <div>
        <span ref="likes">{this.state.likes} </span>
        <button
          onClick = {e => this.handleClick(e, productId)}
          ref="likeBtn"
          className={this.state.colorButton}
          uk-icon={this.state.iconButton}
          uk-tooltip={this.state.tooltip}
        >
        </button>
      </div>

    )
  }
}
