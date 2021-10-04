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
        devidIdPH.text = "Device id:"
        devidIdPH.font =  UIFont.init(name: "HelveticaNeue-Light", size: 28.0)
        deviceNamePH.font = UIFont.init(name: "HelveticaNeue-Light", size: 28.0)
        bgView.layer.cornerRadius = 10
        bgView.layer.borderWidth = 1.0
        
    }
    
    func setupData(_ id: String,_ device: String, image: String) {
        deviceIdLabel.text = id
        deviceNameLabel.text = device
        deviceImage.sd_setImage(with: URL(string: image), completed: nil)
    }
}
