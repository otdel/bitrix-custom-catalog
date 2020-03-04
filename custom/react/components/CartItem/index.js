import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';

@inject('cartStore')
@observer
export default class extends React.Component {
    handleRemoveClick = item => {
        this.props.cartStore.removeProduct(item);
        this.refs.btnRemove.setAttribute("disabled", "disabled");
    }
    render() {
        let {item}= this.props;
        return (
            <tr>
                <td>{item.name}</td>
                <td>
                    <a href={item.link}>
                        <img src={item.picture} width="150" height="auto" />
                    </a>
                </td>
                <td>{item.price}</td>
                <td>
                    <button className="uk-button"
                        ref="btnRemove"
                        onClick = {() => this.handleRemoveClick(item)}
                    >
                        <i className="uk-icon" uk-icon="close" ></i>
                            Удалить
                    </button>
                </td>
            </tr>
        );
    }
}