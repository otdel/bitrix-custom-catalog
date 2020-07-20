import React from 'react';

export default function Selector(props) {
  //console.log("selector props", props)
  let featureName = "";
  let featureValues = [];
  let options = [];
  let selectClass = `uk-select ${props.canChange}`

  for (const entry of props.values) {
    let value = entry[0].split("&"); // ["Режим функционирования", "Отвод/Рециркуляция"]
    featureName = value[0] === "null" ? "Характеристика" : value[0]
    featureName = props.featureCode === "cookerHoodType" ? "Тип вытяжки" : value[0]
    featureValues.push(value[1])
  }

  if (featureValues.length > 1) {
    options.push(<option key={0} value="">Выбрать {featureName}</option>)      
  }

  featureValues.map((value, i) => {
    options.push(<option key={i+1} value={`${props.featureCode}&${value}`}>{value}</option>);
  })

  return (
    <div className="uk-margin uk-width-1-3@s" >
      <label className="uk-form-label">{featureName}</label>
      <div className="uk-form-controls">
        <select className={selectClass} onChange={(e) => {props.handleChange(e)}}>
          {options}
        </select>
      </div>
    </div>
  )
}

