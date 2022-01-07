//
//  DeviceRegisterCell.swift
//  Streamly
//
//  Created by Aditya Joshi on 04/10/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class DeviceRegisterCell: UITableViewCell {

    @IBOutlet weak var devidIdPH: UILabel!
    @IBOutlet weak var deviceNamePH: UILabel!
    @IBOutlet weak var deviceIdLabel: UILabel!
    @IBOutlet weak var deviceNameLabel: UILabel!
    @IBOutlet weak var bgView: UIView!
    @IBOutlet weak var deviceImage: UIImageView!
    @IBOutlet weak var sessionIDLabel: UILabel!
    @IBOutlet weak var sessionIDPHLabel: UILabel!
    
    
    static let identifier = String(describing: DeviceRegisterCell.self)
    
    override func awakeFromNib() {
        super.awakeFromNib()
        configureCell()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

    
    private func configureCell() {
        deviceNamePH.text = "Device Name:"
        deviceIdLabel.font =  UIFont.init(name: "HelveticaNeue-Medium", size: 22.0)
        deviceNameLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 22.0)
        sessionIDLabel.font =  UIFont.init(name: "HelveticaNeue-Light", size: 22.0)
        devidIdPH.text = "Device id:"
        sessionIDPHLabel.text = "Personal Name:"
        devidIdPH.font =  UIFont.init(name: "HelveticaNeue-Light", size: 28.0)
        deviceNamePH.font = UIFont.init(name: "HelveticaNeue-Light", size: 28.0)
        sessionIDPHLabel.font = UIFont.init(name: "HelveticaNeue-Light", size: 28.0)
        bgView.layer.cornerRadius = 10
        bgView.layer.borderWidth = 1.0
        
    }
    
    func setupData(_ id: String,_ device: String, image: String, sessionId: String) {
        deviceIdLabel.text = id
        deviceNameLabel.text = device
        sessionIDLabel.text = sessionId
        deviceImage.sd_setImage(with: URL(string: image), completed: nil)
        
    }
}
